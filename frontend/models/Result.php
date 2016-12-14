<?php
namespace frontend\models;

use Yii;
use yii\sphinx\MatchExpression;
use yii\helpers\ArrayHelper;
use common\models\Article;
use common\modules\eav\CategoryCollection;
/**
 * Signup form
 */
class Result
{
    
    public static $value = [];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }
    
   
    public static function initData($resultData) {
        
        $formatData = self::formatData($resultData);
        self::setValue($formatData);
    }
    
    protected static function setValue($formatData) {
        
        foreach ($formatData as $k=>$v) {
            
            switch ($k) {
                case 'article':
                    self::$value[$k] = self::getArticles($v);
                    break;
            }
            
        }
    }
    
    protected static function getArticles($ids) {
        
        $articles = Article::find()
                        ->select(['id', 'title', 'seo', 'availability', 'created_at'])
                        ->where(['enabled' => 1, 'id' => $ids])
                        ->all();
                        
        $categoryCollection = Yii::createObject(CategoryCollection::class);
        $categoryCollection->setAttributeFilter(['teaser', 'abstract']);
        $categoryCollection->initCollection(Article::tableName(), $ids);
        $values = $categoryCollection->getValues();
        $articlesCollection = [];

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
