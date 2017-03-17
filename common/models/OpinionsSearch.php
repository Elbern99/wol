<?php

namespace common\models;

use Yii;
use common\contracts\SearchModelInterface;
use yii\sphinx\MatchExpression;

/**
 * This is the model class for index "opinionsIndex".
 *
 * @property integer $id
 * @property string $description
 * @property string $name
 * @property string $title
 */
class OpinionsSearch extends \yii\sphinx\ActiveRecord implements SearchModelInterface
{
    const SEARCH_LIMIT = 500;
    
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'opinionsIndex';
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
            [['description', 'name', 'title'], 'string']
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
            'name' => Yii::t('app', 'Name'),
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
        return ['opinionsIndex' => 7];
    }
}
