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


    public static function getSearchAjaxResult($searchPhrase)
    {

        $match = new MatchExpression();

        if ($searchPhrase) {

            $match->match(Yii::$app->sphinx->escapeMatchValue($searchPhrase));

            $data = self::find()
                ->select(['id', 'max_version', 'version','w' => new \yii\db\Expression('max_version-version'),])
                ->match($match)
                ->orderBy(['w' => SORT_ASC, 'WEIGHT()' => SORT_DESC])
                ->asArray()
                ->all();

            $ids = ArrayHelper::getColumn($data, 'id');

            if (count($ids)) {
                return self::filterArticleResult($ids);
            }
        }

        return [];
    }


    public static function getIndexWeight()
    {
        return ['articlesIndex' => 10];
    }


    public static function getIndexedFields()
    {
        return [
            'availability',
            'title',
            'publisher',
            'surname',
            'name',
            'url',
            'value'
        ];
    }


    protected static function filterArticleResult(array $ids): array
    {

        return Article::find()
                ->select(['id', 'title'])
                ->where(['id' => $ids, 'enabled' => 1])
                ->orderBy([ new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')])
                ->asArray()
                ->all();
    }
}
