<?php

namespace common\models;

use Yii;
use common\contracts\SearchModelInterface;
use yii\sphinx\MatchExpression;

/**
 * This is the model class for index "test1".
 *
 * @property integer $id
 * @property string $seo
 */
class ArticleSearch extends \yii\sphinx\ActiveRecord implements SearchModelInterface
{
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return '{{%articlesIndex}}';
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

            $match->andMatch(['value' => Yii::$app->sphinx->escapeMatchValue($attributes['exact_phrase'])]);
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

            $match->andMatch('(title|value|availability) (' . implode(' | ', array_keys($filter)) . ')', $filter);
        }

        if ($attributes['any_words']) {

            $anyWords = explode(',', Yii::$app->sphinx->escapeMatchValue($attributes['any_words']));

            $filter = [];

            foreach ($anyWords as $key => $word) {
                $filter[':' . $key] = $word;
            }

            $match->andMatch('(title|value|availability) -(' . implode(' | ', array_keys($filter)) . ')', $filter);
        }

        return  self::find()
                        ->select(['id'])
                        ->match($match)
                        ->asArray()
                        ->all();
    }
}
