<?php

namespace common\models;

use Yii;
use common\contracts\SearchModelInterface;
use yii\sphinx\MatchExpression;

/**
 * This is the model class for index "policypapersIndex".
 *
 * @property integer $id
 * @property string $creator
 * @property string $title
 * @property string $description
 * @property string $type
 */
class PolicypapersSearch extends \yii\sphinx\ActiveRecord implements SearchModelInterface
{
    const SEARCH_LIMIT = 500;
    
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'policypapersIndex';
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
            [['creator', 'title', 'description', 'type'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creator' => 'Creator',
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'Type',
        ];
    }
    
    public static function getSearchAjaxResult($searchPhrase) {
        
        $match = new MatchExpression();
        
        if ($searchPhrase) {

            $match->match(Yii::$app->sphinx->escapeMatchValue($searchPhrase));
            
            $data = self::find()
                            ->select(['id', 'title'])
                            ->match($match)
                            ->asArray()
                            ->all();
            if (count($data)) {
                return $data;
            }
        }
        
        return [];
    }

    public static function getIndexWeight() {
        return [self::indexName() => 8];
    }
    
    public static function getIndexedFields() {
        return [
            'creator',
            'title',
            'description',
        ];
    }
}
