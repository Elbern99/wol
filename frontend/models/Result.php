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

/**
 * Signup form
 */
class Result {

    protected static $value = [];
    protected static $topValue = [];
    public static $formatData = [];
    public static $articleCategoryIds = [];
    public static $biographyFilter = [];
    public static $topicsFilter = [];
    private static $filters = false;
    private static $model;
    
    

    public function setModel(SearchInterface $model) {
        self::$model = $model;
    }

    public static function initData($resultData) {
        self::$formatData = self::formatData($resultData);
        self::setValue(self::$formatData);
    }

    public static function getFilter($key = null) {

        if (is_array(self::$filters) && !is_null($key)) {
            return self::$filters[$key] ?? null;
        }

        return self::$filters;
    }

    public static function setFilter($key, $filter) {
        self::$filters[$key] = $filter;
    }

    protected static function setValue($formatData) {

        $filtered = false;

        if (is_array(self::$filters) && array_key_exists('types', self::$filters)) {
            $filtered = true;
        }

        foreach ($formatData as $k => $v) {

            if ($filtered) {

                $id = self::$model->getHeadingModelKey($k);
                if (is_null(self::$filters['types']) || (isset($id) && (array_search($id, self::$filters['types']) === false))) {
                    continue;
                }
            }

            $func = 'get' . str_replace('_', '', ucwords($k));
            forward_static_call(array(self::class, $func), $v, $k);
        }
    }

    protected static function getKeyTopics($ids, $k) {
        
        self::$topicsFilter = Topic::find()
                                    ->select(['id', 'title'])
                                    ->where(['id' => $ids])
                                    ->orderBy(['created_at' => SORT_DESC])
                                    ->asArray()
                                    ->all();
        
        $query = Topic::find()
                        ->select(['id', 'title', 'url_key', 'short_description', 'created_at', 'image_link'])
                        ->where(['id' => $ids]);
        
        $filtered = false;

        if (is_array(self::$filters) && array_key_exists('topics', self::$filters)) {

            if (self::$filters['topics']) {
                $filtered = true;
            } elseif (count(self::$topicsFilter) && is_null(self::$filters['topics'])) {
                return false;
            }
        } 

        if ($filtered) {
            $query->andWhere(['id' => self::$filters['topics']]);
        } else {
            $query->andWhere(['id' => $ids]);
        }
        
        $order = (is_null(Yii::$app->request->get('sort'))) ? 'relevance' : Yii::$app->request->get('sort');
        
        if ($order != 'relevance') {
            $query->orderBy(['created_at' => SORT_DESC]);
        } else {
            $query->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);
        }

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
                ->where(['id' => $ids]);
        
        $order = (is_null(Yii::$app->request->get('sort'))) ? 'relevance' : Yii::$app->request->get('sort');
        
        if ($order != 'relevance') {
            $query->orderBy(['created_at' => SORT_DESC]);
        } else {
            $query->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);
        }

        $result = $query->asArray()->all();

        self::addDataToValue($result, $k);
    }

    protected static function getEvents($ids, $k) {

        $query = Event::find()
                ->select(['id', 'title', 'url_key', 'location', 'date_to', 'date_from'])
                ->where(['id' => $ids]);
        
        $order = (is_null(Yii::$app->request->get('sort'))) ? 'relevance' : Yii::$app->request->get('sort');
        
        if ($order != 'relevance') {
            $query->orderBy(['date_to' => SORT_DESC]);
        } else {
            $query->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);
        }

        $result = $query->asArray()->all();

        self::addDataToValue($result, $k);
    }

    protected static function getVideos($ids, $k) {

        $query = Video::find()
                        ->select(['id', 'title', 'url_key'])
                        ->where(['id' => $ids]);
        
        $order = (is_null(Yii::$app->request->get('sort'))) ? 'relevance' : Yii::$app->request->get('sort');
        
        if ($order != 'relevance') {
            $query->orderBy(['order' => SORT_ASC]);
        } else {
            $query->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);
        }

        $result = $query->asArray()->all();
        
        self::addDataToValue($result, $k);
    }

    protected static function getNews($ids, $k) {

        $query = NewsItem::find()
                ->select(['id', 'title', 'url_key', 'short_description', 'created_at', 'editor'])
                ->where(['id' => $ids]);

        $order = (is_null(Yii::$app->request->get('sort'))) ? 'relevance' : Yii::$app->request->get('sort');
        
        if ($order != 'relevance') {
            $query->orderBy(['created_at' => SORT_DESC]);
        } else {
            $query->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);
        }

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
           
        self::$biographyFilter = Author::find()
                                        ->select(['id', 'name'])
                                        ->where(['enabled' => 1, 'id' => $ids])
                                        ->asArray()
                                        ->all();

        $query = Author::find()
                    ->select(['id', 'url_key', 'name', 'avatar'])
                    ->where(['enabled' => 1]);
                
        $filtered = false;

        if (is_array(self::$filters) && array_key_exists('biography', self::$filters)) {

            if (self::$filters['biography']) {
                $filtered = true;
            } elseif (count(self::$biographyFilter) && is_null(self::$filters['biography'])) {
                return false;
            }
        }
        
        if ($filtered) {
            $query->andWhere(['id' => self::$filters['biography']]);
        } else {
            $query->andWhere(['id' => $ids]);
        }
        
        $order = (is_null(Yii::$app->request->get('sort'))) ? 'relevance' : Yii::$app->request->get('sort');
        
        if ($order != 'relevance') {
            $query->orderBy(['surname' => SORT_ASC]);
        } else {
            $query->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')]);
        }
        
        $authors = $query->asArray()->all();

        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->setAttributeFilter(['affiliation']);
        $authorCollection->initCollection(Author::tableName(), ArrayHelper::getColumn($authors, 'id'));
        $authorValues = $authorCollection->getValues();

        foreach ($authors as $author) {

            $affiliation = EavValueHelper::getValue($authorValues[$author['id']], 'affiliation', function($data) {
                return $data->affiliation;
            }, 'string');
            
            $params = [
                'id' => $author['id'],
                'url' => Author::getAuthorUrl($author['url_key']),
                'avatar' => Author::getImageUrl($author['avatar']),
                'name' => $author['name'],
                'affiliation' => $affiliation,
            ];
            
            self::$value[] = [
                'params' => $params,
                'type' => $k
            ];
            
            $serched = str_replace(' ', '|',  self::$model->search_phrase);

            if (preg_match("/($serched)/i", $author['name'])) {

                self::$topValue[] = [
                    'params' => $params,
                    'type' => 'authors'
                ];
            }
        }
    }

    protected static function getArticle($ids, $k) {

        $categories = ArticleCategory::find()
                ->select(['category_id', 'COUNT(article_id) AS cnt'])
                ->where(['article_id' => $ids])
                ->groupBy('category_id')
                ->asArray()
                ->all();

        self::$articleCategoryIds = ArrayHelper::map($categories, 'category_id', 'cnt');

        $filtered = false;

        if (is_array(self::$filters) && array_key_exists('subject', self::$filters)) {

            if (self::$filters['subject']) {
                $filtered = true;
            }

        }

        $articles = Article::find()
                ->alias('a')
                ->select(['a.id as id', 'a.title', 'a.seo', 'a.created_at'])
                ->with(['articleAuthors.author' => function($query) {
                    return $query->alias('au')->where(['au.enabled' => 1]);
                }])
                ->where(['a.enabled' => 1, 'a.id' => $ids]);

        if ($filtered) {
            $articles->innerJoin(ArticleCategory::tableName() . ' AS ac', 'ac.article_id = a.id');
            $articles->andWhere(['ac.category_id' => self::$filters['subject']]);
        }
        
        $order = (is_null(Yii::$app->request->get('sort'))) ? 'relevance' : Yii::$app->request->get('sort');
        
        if ($order != 'relevance') {
            $articles->orderBy(['a.created_at' => SORT_DESC]);
        } else {
            $articles->orderBy([new \yii\db\Expression('FIELD (`a`.`id`, ' . implode(',', $ids) . ')')]);
        }

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

    public function getSearchValue() {

        return self::$value;
    }
    
    public function getSearchTopValue() {
        
        return self::$topValue;
    }

    protected static function formatData($data) {

        $format = [];
        
        if (count($data)) {
            foreach ($data as $d) {
                $format[$d['type']][] = $d['id'];
            }
        }

        return $format;
    }

}
