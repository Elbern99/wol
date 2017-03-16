<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
use frontend\models\contracts\SearchInterface;
use frontend\components\search\ResultStrategy;
use yii\sphinx\Query;
use yii\sphinx\MatchExpression;

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
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function search() {
        
        $types = Yii::$app->params['search'];
        $froms = [];
        $fieldsWeight = ['title' => 100, 'name' => 50, 'url' => 40];
        $fields = ['title', 'description', 'body', 'location', 'name', 'editor', 'url', 'value', 'surname', 'availability'];
        $limit = 0;

        foreach($this->types as $type) {
            
            $modelType = $this->headingModel[$type];
            
            if (!isset($types[$modelType])) {
                throw new \Exception('Search Model Not Found');
            }
            
            $class = $types[$modelType];
            $froms = array_merge($froms, $class::getIndexWeight());

            $limit += $class::SEARCH_LIMIT;
        }

        $sphinx = new \SphinxClient();
        $query = new Query;

        $sphinx->setSelect('id, type');
        $sphinx->setSortMode(SPH_SORT_RELEVANCE);
        $sphinx->setFieldWeights($fieldsWeight);
        $sphinx->setIndexWeights($froms);

        $sphinx->setLimits(0, $limit);
        $params = $this->getSearchMatch($this->getAttributes(), $fields);
        $sql = $query->match($params)->createCommand()->getRawSql();
        
        preg_match('/\(.+\)/',$sql, $m);
        
        if (isset($m[0])) {
            $args = preg_replace('/(\(\'|\'\))/', '', $m[0]);
        } else {
            $args = $this->search_phrase;
        }

        $results = $sphinx->query($args, implode(',', array_keys($froms)));
        
        $searched = [];
        /*echo '<pre>';
        var_dump($results);
        echo '</pre>';
        exit;*/
        if (isset($results['matches'])) {
            
            foreach ($results['matches'] as $match) {
                $searched[] = $match['attrs'];
            }
        }

        return $searched;
    }
    
    public function setSelectedTypes() {
        $this->types = $this->getTypeIds();
    }
    
    protected function getSearchMatch($attributes, $fields) {
        
        $match = new MatchExpression();
        $fields = implode('|', $fields);

        if ($attributes['search_phrase']) {

            $match->match(Yii::$app->sphinx->escapeMatchValue($attributes['search_phrase']));
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
