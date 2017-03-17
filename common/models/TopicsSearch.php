<?php

namespace common\models;

use Yii;
use common\contracts\SearchModelInterface;
use yii\sphinx\MatchExpression;

/**
 * This is the model class for index "topicsIndex".
 *
 * @property integer $id
 * @property string $description
 * @property string $title
 */
class TopicsSearch extends \yii\sphinx\ActiveRecord implements SearchModelInterface
{
    const SEARCH_LIMIT = 50;
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'topicsIndex';
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
            [['description', 'title'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'description' => Yii::t('app', 'Description'),
            'title' => Yii::t('app', 'Title'),
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
        return ['topicsIndex' => 8];
    }

}
