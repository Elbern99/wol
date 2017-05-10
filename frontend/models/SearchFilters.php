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
class SearchFilters {

    public static $articleCategoryIds = [];
    public static $biographyFilter = [];
    public static $topicsFilter = [];
    private static $filters = false;

    public static function getFilter($key = null) {

        if (is_array(self::$filters) && !is_null($key)) {
            return self::$filters[$key] ?? null;
        }

        return self::$filters;
    }

    public static function setFilter($key, $filter) {
        self::$filters[$key] = $filter;
    }
    
    public static function initFilterData($formatData) {
        
        foreach ($formatData as $k => $v) {
            $func = 'get' . str_replace('_', '', ucwords($k));
            forward_static_call(array(self::class, $func), $v);
        }
    }
    
    protected static function getNews($ids) { }
    
    protected static function getOpinions($ids) { }

    protected static function getEvents($ids) { }

    protected static function getPapers($ids) { }
    
    protected static function getPolicypapers($ids) { }
    
    protected static function getVideos($ids) { }

    protected static function getKeyTopics($ids) {
        
        self::$topicsFilter = Topic::find()
                                    ->select(['id', 'title'])
                                    ->where(['id' => $ids])
                                    ->orderBy('title')
                                    ->asArray()
                                    ->all();
    }


    protected static function getBiography($ids) {
           
        self::$biographyFilter = Author::find()
                                        ->select(['id', 'name'])
                                        ->where(['enabled' => 1, 'id' => $ids])
                                        ->orderBy('surname')
                                        ->asArray()
                                        ->all();

        
    }

    protected static function getArticle($ids) {

        $categories = ArticleCategory::find()
                ->select(['category_id', 'COUNT(article_id) AS cnt'])
                ->where(['article_id' => $ids])
                ->groupBy('category_id')
                ->asArray()
                ->all();

        self::$articleCategoryIds = ArrayHelper::map($categories, 'category_id', 'cnt');
    }


}
