<?php

namespace common\models;

use Yii;
use common\contracts\SearchModelInterface;
use yii\sphinx\MatchExpression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for index "test1".
 *
 * @property integer $id
 * @property string $seo
 */
class ArticleSearch extends \yii\sphinx\ActiveRecord implements SearchModelInterface
{
    const SEARCH_LIMIT = 500;
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'articlesIndex';
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
            [['seo'], 'string'],
            [['availability'], 'string'],
            [['publisher'], 'string'],
            [['url'], 'string'],
            [['surname'], 'string'],
            [['title'], 'string'],
            [['value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seo' => 'Seo',
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
            $results = self::filterArticleResult($ids);
            
            if (count($results)) {
                return $results;
            }
        }
        
        return [];
    }

    public static function getIndexWeight() {
        return ['articlesIndex' => 10];
    }

    protected static function filterArticleResult(array $ids): array {
        
        return Article::find()
                    ->select(['id', 'title'])
                    ->where(['id' => $ids, 'enabled' => 1])
                    ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',',$ids) . ')')])
                    ->asArray()
                    ->all();
    }
}
