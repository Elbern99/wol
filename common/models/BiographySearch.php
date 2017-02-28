<?php

namespace common\models;

use Yii;
use common\contracts\SearchModelInterface;
use yii\sphinx\MatchExpression;

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
                            ->select('name as title')
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

            $match->andMatch('(name|value|url) (' . implode(' | ', array_keys($filter)) . ')', $filter);
        }

        if ($attributes['any_words']) {

            $anyWords = explode(',', Yii::$app->sphinx->escapeMatchValue($attributes['any_words']));

            $filter = [];

            foreach ($anyWords as $key => $word) {
                $filter[':' . $key] = $word;
            }

            $match->andMatch('(name|value|url) -(' . implode(' | ', array_keys($filter)) . ')', $filter);
        }

        $result = self::find()
                        ->select(['id'])
                        ->match($match)
                        ->addOptions(['field_weights' => ['name' => 50, 'url' => 30, 'value' => 10]])
                        ->limit(self::SEARCH_LIMIT)
                        ->asArray()
                        ->all();
        
        if (count($result)) {
            return self::filterAuthorResult($result);
        }
        
        return [];
    }
    
    protected static function filterAuthorResult(array $ids): array {
        
        return Author::find()
                ->select('id')
                ->where(['id' => $ids, 'enabled' => 1])
                ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', \yii\helpers\ArrayHelper::getColumn($ids, 'id')) . ')')])
                ->asArray()
                ->all();
    }
}
