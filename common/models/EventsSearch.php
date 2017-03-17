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
    
    public static function getIndexWeight() {
        return ['eventsIndex' => 8];
    }
    
    public static function getIndexedFields() {
        return [
            'body',
            'title',
            'location'
        ];
    }
    
}
