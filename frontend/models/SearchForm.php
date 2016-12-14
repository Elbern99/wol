<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
use yii\sphinx\MatchExpression;
use yii\helpers\ArrayHelper;
/**
 * Signup form
 */
class SearchForm extends Model
{
    public $types;
    public $search_phrase;
    public $exact_phrase;
    public $all_words;
    public $one_more_words;
    public $any_words;
    
    const ARTICLE_SEARCH_TYPE = 1;
    const BIOGRAPHY_SEARCH_TYPE = 2;
    
    private $headingModel = [
        self::ARTICLE_SEARCH_TYPE => 'article',
    ];
    
    private $headingLabel = [
        self::ARTICLE_SEARCH_TYPE => 'Article',
        self::BIOGRAPHY_SEARCH_TYPE => 'Biography'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['types', 'required'],
            [['search_phrase', 'exact_phrase', 'all_words', 'one_more_words', 'any_words'], 'string', 'max'=>100],
        ];
    }
    
    public static function getHeadingFilterStatic() {
        return $this->headingLabel;
    }
    
    public function getHeadingFilter() {
        return $this->headingLabel;
    }
    

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function search() {
        
        $types = Yii::$app->params['search'];
        $result = [];

        foreach($this->types as $type) {
            
            $modelType = $this->headingModel[$type];
            
            if (!isset($types[$modelType])) {
                throw new \Exception('Search Model Not Found');
            }
            
            $class = $types[$modelType];
            $match = new MatchExpression();
            
            if ($this->search_phrase) {
                
                $match->match(Yii::$app->sphinx->escapeMatchValue($this->search_phrase));
            }
            
            if ($this->exact_phrase) {
                
                $match->andMatch(['value' => Yii::$app->sphinx->escapeMatchValue($this->exact_phrase)]);
            }

            if ($this->all_words) {
                
                $allWords = explode(',', Yii::$app->sphinx->escapeMatchValue($this->all_words));
                $match->andMatch(['*' => $allWords]);
            }

            if ($this->one_more_words) {
                
                $oneMoreWords = explode(',', Yii::$app->sphinx->escapeMatchValue($this->one_more_words));
                
                $filter = [];
                
                foreach ($oneMoreWords as $key=>$word) {
                    $filter[':'.$key] = $word;
                }
                
                $match->andMatch('(title|value|availability) ('.  implode(' | ', array_keys($filter)).')', $filter);
            }

            if ($this->any_words) {
                
                $anyWords = explode(',', Yii::$app->sphinx->escapeMatchValue($this->any_words));
                
                $filter = [];
                
                foreach ($anyWords as $key=>$word) {
                    $filter[':'.$key] = $word;
                }
                
                $match->andMatch('(title|value|availability) -('.  implode(' | ', array_keys($filter)).')', $filter);
            }

            $data = $class::find()
                            ->select(['id'])
                            ->match($match)
                            ->asArray()
                            ->all();

            foreach ($data as $d) {
                $result[] = [
                    'type' => $modelType,
                    'id' => $d['id']
                ];
            }

        }
        
        return $result;
    }
}
