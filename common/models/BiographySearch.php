<?php

namespace common\models;

use Yii;
use common\contracts\SearchModelInterface;
use yii\sphinx\MatchExpression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for index "biographyIndex".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $url_key
 */
class BiographySearch extends \yii\sphinx\ActiveRecord implements SearchModelInterface
{
    const SEARCH_LIMIT = 500;
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'biographyIndex';
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
            [['name', 'value', 'url_key', 'url'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'url_key' => Yii::t('app', 'Url Key'),
        ];
    }
    
    public static function getSearchAjaxResult($searchPhrase) {
        
        $match = new MatchExpression();
        
        if ($searchPhrase) {

            $match->match(Yii::$app->sphinx->escapeMatchValue($searchPhrase));
            
            $data = self::find()
                            ->select(['id'])
                            ->match($match)
                            ->asArray()
                            ->all();
            
            $ids = ArrayHelper::getColumn($data, 'id');
            $results = self::filterAuthorResult($ids);
            
            if (count($results)) {
                return $results;
            }
        }
        
        return [];
    }

    public static function getIndexWeight() {
        return ["biographyIndex" => 9];
    }
    
    public static function getIndexedFields() {
        return [
            'name',
            'title',
            'url',
            'value'
        ];
    }

    protected static function filterAuthorResult(array $ids): array {
        
        return Author::find()
                ->select(['id', 'name as title'])
                ->where(['id' => $ids, 'enabled' => 1])
                ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')])
                ->asArray()
                ->all();
    }
}
