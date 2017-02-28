<?php

namespace common\models;

use Yii;
use common\contracts\SearchModelInterface;
use yii\sphinx\MatchExpression;

/**
 * This is the model class for index "eventsIndex".
 *
 * @property integer $id
 * @property string $body
 * @property string $title
 * @property string $location
 */
class EventsSearch extends \yii\sphinx\ActiveRecord implements SearchModelInterface
{
    const SEARCH_LIMIT = 500;
    
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'eventsIndex';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'unique'],
            [['id'], 'integer'],
            [['body', 'title', 'location'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'body' => Yii::t('app', 'Body'),
            'title' => Yii::t('app', 'Title'),
            'location' => Yii::t('app', 'Location'),
        ];
    }
    
    public static function getSearchAjaxResult($searchPhrase) {
        
        $match = new MatchExpression();
        
        if ($searchPhrase) {

            $match->match(Yii::$app->sphinx->escapeMatchValue($searchPhrase));
            
            $data = self::find()
                            ->select('title')
                            ->match($match)
                            ->asArray()
                            ->all();
            
            return $data;
        }
        
        return [];
    }
    
    public static function getSearchResult($attributes) {

        $match = new MatchExpression();
        
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

            $filter = [];

            foreach ($oneMoreWords as $key => $word) {
                $filter[':' . $key] = $word;
            }

            $match->andMatch('(title|body|location) (' . implode(' | ', array_keys($filter)) . ')', $filter);
        }

        if ($attributes['any_words']) {

            $anyWords = explode(',', Yii::$app->sphinx->escapeMatchValue($attributes['any_words']));

            $filter = [];

            foreach ($anyWords as $key => $word) {
                $filter[':' . $key] = $word;
            }

            $match->andMatch('(title|body|location) -(' . implode(' | ', array_keys($filter)) . ')', $filter);
        }

        return  self::find()
                        ->select(['id'])
                        ->match($match)
                        ->limit(self::SEARCH_LIMIT)
                        ->asArray()
                        ->all();
        
    }

}
