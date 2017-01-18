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
    public static $formatData = [];
    public static $articleCategoryIds = [];
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

            $func = 'get' . str_replace(' ', '', ucwords($k));
            forward_static_call(array(self::class, $func), $v, $k);
        }
    }

    protected static function getKeyTopics($ids, $k) {

        $result = Topic::find()
                ->select(['title', 'url_key', 'short_description', 'created_at'])
                ->where(['id' => $ids])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();

        self::addDataToValue($result, $k);
    }

    protected static function getOpinions($ids, $k) {

        $result = Opinion::find()
                ->select(['title', 'url_key', 'short_description', 'created_at'])
                ->where(['id' => $ids])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();

        self::addDataToValue($result, $k);
    }

    protected static function getEvents($ids, $k) {

        $result = Event::find()
                ->select(['title', 'url_key', 'location', 'date_to', 'date_from'])
                ->where(['id' => $ids])
                ->orderBy(['date_to' => SORT_DESC])
                ->asArray()
                ->all();

        self::addDataToValue($result, $k);
    }

    protected static function getVideos($ids, $k) {

        $result = Video::find()
                ->select(['title', 'url_key'])
                ->where(['id' => $ids])
                ->orderBy(['order' => SORT_ASC])
                ->asArray()
                ->all();
        self::addDataToValue($result, $k);
    }

    protected static function getNews($ids, $k) {

        $result = NewsItem::find()
                ->select(['title', 'url_key', 'short_description', 'created_at', 'editor'])
                ->where(['id' => $ids])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();

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

        $authors = Author::find()
                ->select(['id', 'url_key', 'name'])
                ->where(['enabled' => 1, 'id' => $ids])
                ->asArray()
                ->all();

        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->setAttributeFilter(['affiliation']);
        $authorCollection->initCollection(Author::tableName(), ArrayHelper::getColumn($authors, 'id'));
        $authorValues = $authorCollection->getValues();

        foreach ($authors as $author) {

            $affiliation = EavValueHelper::getValue($authorValues[$author['id']], 'affiliation', function($data) {
                        return $data->affiliation;
                    }, 'string');

            self::$value[] = [
                'params' => [
                    'url' => Author::getAuthorUrl($author['url_key']),
                    'name' => $author['name'],
                    'affiliation' => $affiliation,
                ],
                'type' => $k
            ];
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

        $order = !Yii::$app->request->get('sort') ? SORT_DESC : SORT_ASC;
        $filtered = false;

        if (is_array(self::$filters) && array_key_exists('subject', self::$filters)) {

            if (is_null(self::$filters['subject'])) {
                return $articlesCollection;
            }

            $filtered = true;
        }

        $articles = Article::find()
                ->alias('a')
                ->select(['a.id', 'a.title', 'a.seo', 'a.availability', 'a.created_at'])
                ->where(['a.enabled' => 1, 'a.id' => $ids]);

        if ($filtered) {

            $articles->innerJoin(ArticleCategory::tableName() . ' AS ac', 'ac.article_id = a.id');
            $articles->andWhere(['ac.category_id' => self::$filters['subject']]);
        }

        $articles = $articles->orderBy(['created_at' => $order])->all();

        $categoryCollection = Yii::createObject(CategoryCollection::class);
        $categoryCollection->setAttributeFilter(['teaser', 'abstract']);
        $categoryCollection->initCollection(Article::tableName(), $ids);
        $values = $categoryCollection->getValues();


        foreach ($articles as $article) {

            self::$value[] = [
                'params' => [
                    'title' => $article->title,
                    'url' => '/articles/' . $article->seo,
                    'availability' => $article->availability,
                    'teaser' => unserialize($values[$article->id]['teaser']),
                    'abstract' => unserialize($values[$article->id]['abstract']),
                    'created_at' => $article->created_at,
                ],
                'type' => $k
            ];
        }
    }

    public function getSearchValue() {

        return self::$value;
    }

    protected static function formatData($data) {

        $format = [];

        foreach ($data as $d) {
            $format[$d['type']][] = $d['id'];
        }

        return $format;
    }

}
