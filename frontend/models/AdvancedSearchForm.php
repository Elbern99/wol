<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
use frontend\models\contracts\SearchInterface;
use frontend\components\search\ResultStrategy;
use yii\sphinx\Query;
use yii\sphinx\MatchExpression;
use common\models\SynonymsSearch;

require_once ('api/sphinxapi.php');
/**
 * Signup form
 */
class AdvancedSearchForm extends Model implements SearchInterface
{
    use traits\SearchTrait;
    
    public $types;
    public $search_phrase;
    public $exact_phrase;
    public $all_words;
    public $one_more_words;
    public $any_words;
    public $synonyms;
    private $forbidden = ['\\', '/' , '(', ')', '|', '!', '@', '~', '&', '^', '$', '=', '>', '<', "\x00", "\n", "\r", "\x1a", '?'];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['types', 'required'],
            ['search_phrase', 'required'],
            ['search_phrase', 'string', 'min'=>3,'max'=>255],
            [['exact_phrase', 'all_words', 'one_more_words', 'any_words'], 'string', 'max'=>100],
        ];
    }
    
    /*
     * Seach by ajax for pre result
     */
    public function searchAjax() {

        $types = Yii::$app->params['search'];
        $result = [];
        
        foreach($this->getheadingModelFilter() as $modelType) {
            
            if (!isset($types[$modelType])) {
                throw new \Exception('Search Model Not Found');
            }
            
            $class = $types[$modelType];
            $data = $class::getSearchAjaxResult($this->search_phrase);

            foreach ($data as $t) {
                $result[] = array_merge(['type' => $modelType], $t);
            }

        }

        $comparator = new \frontend\components\search\comparators\TopRelevantComparator($this->search_phrase);
        $sortingResult = new ResultStrategy($result);
        $sortingResult->setComparator($comparator);
        return $sortingResult->sort();
    }
    
    private function removeForbiddenTag($phrase) {
        return str_replace($this->forbidden, '', $phrase);
    }
    
    protected function checkSynonymInPhrase() {
        
        $phrase = $this->removeForbiddenTag($this->search_phrase);

        $words = explode(' ', $phrase);
        $synonyms = SynonymsSearch::getSynonymsFormatArray($words);
        $searchedMatch = '('.$phrase.')';

        if (count($synonyms)) {
            $searchedMatch .= ' | ('.implode(' ', $synonyms).')';
            $this->synonyms = SynonymsSearch::getSynonymWords();
        }

        return $searchedMatch;
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function search() {
        
        $types = Yii::$app->params['search'];
        $searched = [];
        $fieldsWeight = ['title' => 80, 'name' => 80, 'url' => 40, 'value' => 10];
        $fields = ['title', 'description', 'body', 'location', 'name', 'editor', 'url', 'value', 'surname', 'availability'];
        
        $searchPhrase = $this->checkSynonymInPhrase();

        foreach($this->types as $type) {
            
            $modelType = $this->headingModel[$type];
            
            if (!isset($types[$modelType])) {
                throw new \Exception('Search Model Not Found');
            }
            
            $class = $types[$modelType];

            $sphinx = new \SphinxClient();
            $query = new Query;

            $sphinx->setSelect('id, type, title');
            $sphinx->setArrayResult(true);
            $sphinx->setSortMode(SPH_SORT_RELEVANCE);
            $sphinx->setFieldWeights($fieldsWeight);
            $sphinx->setIndexWeights($class::getIndexWeight());

            $sphinx->setLimits(0, $class::SEARCH_LIMIT);
            $params = $this->getSearchMatch($this->getAttributes(), $fields, $searchPhrase);
            $sql = $query->match($params)->createCommand()->getRawSql();

            preg_match('/\(.+\)/',$sql, $m);

            if (isset($m[0])) {
                $args = preg_replace('/(\(\'|\'\))/', '', $m[0]);
            } else {
                $args = $searchPhrase;
            }

            $result = $sphinx->query($args, key($class::getIndexWeight()));

            if (isset($result['matches']) && count($result['matches'])) {
                $searched = array_merge($searched, $result['matches']);
            }
        }

        usort($searched, function ( $a, $b ) {
            if($a['weight'] == $b['weight']){ return 0 ; } 
            return ($a['weight'] > $b['weight']) ? -1 : 1;
        });

        $matches = [];
        
        if (count($searched)) {
            
            foreach ($searched as $match) {
                
                $matches[] = $match['attrs'];
            }
        }

        return $matches;
    }
    
    public function setSelectedTypes() {
        $this->types = $this->getTypeIds();
    }
    
    protected function getSearchMatch($attributes, $fields, $phrase) {
        
        $match = new MatchExpression();
        $fields = implode('|', $fields);

        if ($attributes['search_phrase']) {

            $match->match($phrase);
        }

        if ($attributes['exact_phrase']) {

            $match->andMatch(['*' => Yii::$app->sphinx->escapeMatchValue($attributes['exact_phrase'])]);
        }

        if ($attributes['all_words']) {

            $allWords = explode(',', Yii::$app->sphinx->escapeMatchValue($attributes['all_words']));
            $match->andMatch(['*' => $allWords]);
        }

        if ($attributes['one_more_words']) {

            $oneMoreWords = explode(',', Yii::$app->sphinx->escapeMatchValue($attributes['one_more_words']));

            $oneMoreFilter = [];

            foreach ($oneMoreWords as $key => $word) {
                $oneMoreFilter[':one_more_' . $key] = $word;
            }

            $match->andMatch('('.$fields.') (' . implode(' | ', array_keys($oneMoreFilter)) . ')', $oneMoreFilter);
        }

        if ($attributes['any_words']) {

            $anyWords = explode(',', Yii::$app->sphinx->escapeMatchValue($attributes['any_words']));

            $filter = [];

            foreach ($anyWords as $key => $word) {
                $filter[':any_' . $key] = $word;
            }

            $match->andMatch('('.$fields.') -(' . implode(' | ', array_keys($filter)) . ')', $filter);
        }

        return $match;
    }
}
