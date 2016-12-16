<?php
namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\Article;
use common\models\ArticleCategory;
use common\modules\eav\CategoryCollection;
use frontend\models\contracts\SearchInterface;
/**
 * Signup form
 */
class Result
{
    public static $value = [];
    public static $formatData = [];
    public static $articleCategoryIds = [];
    private static $filters = [];
    private static $model;
    
    public function setModel(SearchInterface $model) {
        self::$model = $model;
    }
    
    public static function initData($resultData) {
        
        self::$formatData = self::formatData($resultData);
        self::setValue(self::$formatData);
    }
    
    public static function setFilter($key, $filter) {
        self::$filters[$key] = $filter;
    }


    protected static function setValue($formatData) {
        
        $filtered = false;

        if (array_key_exists('types', self::$filters)) {
            
            if (is_null(self::$filters['types'])) {
                return;
            }
            
            $filtered = true;
        }

        foreach ($formatData as $k => $v) {

            if ($filtered) {

                $id = self::$model->getHeadingModelKey($k);

                if(isset($id) && (array_search($id, self::$filters['types']) === false)) {
                    continue;
                }

            }

            switch ($k) {
                case 'article':
                    self::$value[$k] = self::getArticles($v);
                    break;
            }
            
        }
    }
    
    protected static function getArticles($ids) {
        
        $order = !Yii::$app->request->get('sort') ? SORT_DESC : SORT_ASC;
        $articlesCollection = [];
        $filtered = false;

        if (array_key_exists('subject', self::$filters)) {
            
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

            $articles->innerJoin(ArticleCategory::tableName().' AS ac', 'ac.article_id = a.id');
            $articles->andWhere(['ac.category_id' => self::$filters['subject']]);
        }
        
        $articles = $articles->orderBy(['created_at' => $order])->all();
        
        $categories = ArticleCategory::find()
                                        ->select(['category_id', 'COUNT(article_id) AS cnt'])
                                        ->groupBy('category_id')
                                        ->asArray()
                                        ->all();
        
        self::$articleCategoryIds = ArrayHelper::map($categories, 'category_id', 'cnt');
        
        $categoryCollection = Yii::createObject(CategoryCollection::class);
        $categoryCollection->setAttributeFilter(['teaser', 'abstract']);
        $categoryCollection->initCollection(Article::tableName(), $ids);
        $values = $categoryCollection->getValues();
       

        foreach ($articles as $article) {
    
            $articlesCollection[$article->id] = [
                'title' => $article->title,
                'url' => '/articles/' . $article->seo,
                'availability' => $article->availability,
                'teaser' => unserialize($values[$article->id]['teaser']),
                'abstract' => unserialize($values[$article->id]['abstract']),
                'created_at' => $article->created_at,
            ];
        }
              
        return $articlesCollection;
    }
    
    protected static function formatData($data) {
        
        $format = [];
        
        foreach ($data as $d) {
            $format[$d['type']][] = $d['id']; 
        }
        
        return $format;
    }
}
