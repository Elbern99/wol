<?php
namespace frontend\controllers\traits;

use Yii;
use common\models\Article;
use common\models\NewsletterNews;
use common\models\NewsItem;
use common\models\Category;
use frontend\components\widget\SidebarWidget;

trait NewsTrait {

    protected function getNewsArchive() {
        
        return NewsItem::find()
                        ->select(['created_at'])
                        ->groupBy(['EXTRACT(YEAR_MONTH FROM created_at)'])
                        ->orderBy(['created_at' => SORT_DESC])
                        ->all();
    }
    
    protected function getNewsletterArchive() {
        
        return NewsletterNews::find()
                                ->select(['title', 'date', 'url_key'])
                                ->orderBy(['date' => SORT_DESC])
                                ->all();
    }
    
    protected function getLastArticlesArchive($limit) {
        
        return Article::find()
                            ->select(['id','seo', 'title'])
                            ->with(['articleAuthors.author' => function($query) {
                                 return $query->select(['id','url_key', 'name'])->asArray();
                            }])
                            ->orderBy(['created_at' => SORT_DESC])
                            ->limit($limit)
                            ->all();
    }
    
    protected function getMainCategory() 
    {
        return Category::find()->where([
            'url_key' => 'news',
        ])
        ->one();
    }
    
    protected function getNewsList($limit = 10, $year = null, $month = null)
    {
        $newsQuery = NewsItem::find()->orderBy('created_at desc');
        
        if ($month && $year) {
           $newsQuery->andWhere([
                'MONTH(created_at)' => $month,
                'YEAR(created_at)' => $year,
            ]);
        }
        else if (!$month && $year) {
            $newsQuery->andWhere([
                'YEAR(created_at)' => $year,
            ]);
        }
        
        return $newsQuery->limit($limit)
                         ->all();
    }
    
    protected function getNewsWidgets() {
        return new SidebarWidget('news');
    }
}

