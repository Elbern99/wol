<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\Article;
use common\models\ArticleCategory;
use common\modules\eav\CategoryCollection;
use frontend\models\contracts\SearchInterface;
use common\models\Author;
use common\modules\eav\helper\EavValueHelper;
use common\models\NewsItem;
use common\models\Video;
use common\models\Event;
use common\models\Opinion;
use common\models\Topic;
use frontend\models\SearchXmlData;

/**
 * Signup form
 */
class Result {

    protected static $value = [];
    protected static $topValue = [];
    public static $formatData = [];
    private static $searchAttributes;
    private static $originResult;
    public static $synonyms;
    
    public function setSearchParams(array $attributes) {
        self::$searchAttributes = $attributes;
    }

    public static function initData($resultData) {
        self::$originResult = $resultData;
        self::$formatData = self::formatData();
        self::setValue(self::$formatData);
    }

    protected static function setValue($formatData) {

        foreach ($formatData as $k => $v) {
            $func = 'get' . str_replace('_', '', ucwords($k));
            forward_static_call(array(self::class, $func), $v, $k);
        }
    }

    protected static function getKeyTopics($ids, $k) {

        $query = Topic::find()
                        ->select(['id', 'title', 'url_key', 'short_description', 'created_at', 'image_link'])
                        ->where(['id' => $ids])
                        ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);
        
        $items = $query->asArray()->all();

        foreach ($items as $item) {

            self::$topValue[] = [
                'params' => $item,
                'type' => $k
            ];
        }
    }

    protected static function getOpinions($ids, $k) {

        $query = Opinion::find()
                ->select(['id', 'title', 'url_key', 'short_description', 'created_at'])
                ->where(['id' => $ids])
                ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);

        $result = $query->asArray()->all();

        self::addDataToValue($result, $k);
    }

    protected static function getEvents($ids, $k) {

        $query = Event::find()
                ->select(['id', 'title', 'url_key', 'location', 'date_to', 'date_from'])
                ->where(['id' => $ids])
                ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);
        
        $result = $query->asArray()->all();

        self::addDataToValue($result, $k);
    }

    protected static function getVideos($ids, $k) {

        $query = Video::find()
                        ->select(['id', 'title', 'url_key'])
                        ->where(['id' => $ids])
                        ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);

        $result = $query->asArray()->all();
        
        self::addDataToValue($result, $k);
    }

    protected static function getNews($ids, $k) {

        $query = NewsItem::find()
                ->select(['id', 'title', 'url_key', 'short_description', 'created_at', 'editor'])
                ->where(['id' => $ids])
                ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);

        $result = $query->asArray()->all();
        
        self::addDataToValue($result, $k);
    }

    protected function addDataToValue($items, $k) {

        foreach ($items as $item) {

            self::$value[] = [
                'params' => $item,
                'type' => $k
            ];
        }
    }

    protected static function getBiography($ids, $k) {

        $query = Author::find()
                    ->select(['id', 'url_key', 'name', 'avatar'])
                    ->where(['enabled' => 1])
                    ->andWhere(['id' => $ids])
                    ->with('authorRolesRelation')
                    ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);

        $authors = $query->all();

        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->setAttributeFilter(['affiliation']);
        $authorCollection->initCollection(Author::tableName(), ArrayHelper::getColumn($authors, 'id'));
        $authorValues = $authorCollection->getValues();

        foreach ($authors as $author) {

            $affiliation = EavValueHelper::getValue($authorValues[$author->id], 'affiliation', function($data) {
                return $data->affiliation;
            }, 'string');
            
            $roles = ArrayHelper::getColumn($author->authorRolesRelation, 'role_id');

            $params = [
                'id' => $author['id'],
                'url' => $author->getAuthorUrlByRoleId($roles),
                'avatar' => $author->getAvatarBaseUrl(),
                'name' => $author->name,
                'affiliation' => $affiliation,
            ];
            
            self::$value[] = [
                'params' => $params,
                'type' => $k
            ];
            
            $serched = str_replace(' ', '|',  self::$searchAttributes['search_phrase']);

            if (is_array(self::$synonyms) && count(self::$synonyms)) {
                $serched .= '|';
                $serched .= implode('|', self::$synonyms);
            }

            if (preg_match("/($serched)/i", $author->name)) {

                self::$topValue[] = [
                    'params' => $params,
                    'type' => 'authors'
                ];
            }
        }
    }

    protected static function getArticle($ids, $k) {

        $articles = Article::find()
                ->alias('a')
                ->select(['a.id as id', 'a.title', 'a.seo', 'a.created_at'])
                ->with(['articleAuthors.author' => function($query) {
                    return $query->alias('au')->where(['au.enabled' => 1]);
                }])
                ->with(['articleCategories' => function($query) {
                    return $query->select(['category_id', 'article_id']);
                }])
                ->where(['a.enabled' => 1, 'a.id' => $ids])
                ->orderBy([new \yii\db\Expression('FIELD (`a`.`id`, ' . implode(',', $ids) . ')')]);

        $articles = $articles->all();

        $categoryCollection = Yii::createObject(CategoryCollection::class);
        $categoryCollection->setAttributeFilter(['teaser', 'abstract']);
        $categoryCollection->initCollection(Article::tableName(), $ids);
        $values = $categoryCollection->getValues();
        
        
        foreach ($articles as $article) {

            $eavValue = $values[$article->id] ?? [];
            $articleOwner = [];
            
            foreach ($article->articleAuthors as $author) { 
                $articleOwner[] = $author['author'];
            }

            self::$value[] = [
                'params' => [
                    'id' => $article->id,
                    'categories' => ArrayHelper::getColumn($article->articleCategories, 'category_id'),
                    'title' => $article->title,
                    'url' => '/articles/' . $article->seo,
                    'authors' => $articleOwner,
                    'teaser' => EavValueHelper::getValue($eavValue, 'teaser', function($data) {
                        return $data;
                    }),
                    'abstract' => EavValueHelper::getValue($eavValue, 'abstract', function($data) {
                        return $data;
                    }),
                    'created_at' => $article->created_at,
                ],
                'type' => $k
            ];
        }
    }
    
    protected static function getPapers($ids, $k) {
        
        $model = new SearchXmlData($k);
        $result = $model->getDataByIds($ids);

        self::addDataToValue($result, $k);
    }
    
    protected static function getPolicypapers($ids, $k) {

        $model = new SearchXmlData($k);
        $result = $model->getDataByIds($ids);
        
        self::addDataToValue($result, $k);
    }

    public function getSearchValue() {

        return self::$value;
    }
    
    public function getSearchTopValue() {
        
        return self::$topValue;
    }
    
    public function getOriginData() {
        return self::$originResult;
    }

    protected static function formatData() {

        $format = [];
        
        if (count(self::$originResult) && self::$originResult) {
            foreach (self::$originResult as $d) {
                $format[$d['type']][] = $d['id'];
            }
        }

        return $format;
    }

}
