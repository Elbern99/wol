<?php
namespace frontend\controllers\traits;

use Yii;
use common\models\Article;
use common\models\NewsletterNews;
use common\models\NewsItem;
use common\models\Category;
use frontend\components\widget\SidebarWidget;
use common\models\NewsWidget;

trait NewsTrait {

    protected function getNewsArchive() {
        
        $data = NewsItem::find()
                        ->select(['DISTINCT (EXTRACT(YEAR_MONTH FROM created_at)) as created_at'])
                        ->orderBy(['created_at' => SORT_DESC])
                        ->asArray()
                        ->andWhere(['enabled' => 1])
                        ->all();
        
        $dates = [];
        
        foreach ($data as $date) {
            $year = substr($date['created_at'], 0, 4);
            $dates[] = $year.'-'.substr($date['created_at'], 4);
        }
        
        return $dates;
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
        
        return $newsQuery->andWhere(['enabled' => 1])->limit($limit)
                         ->all();
    }
    
    protected function getNewsWidgets() {
        return new SidebarWidget('news');
    }
    
    protected function getNewsWidgetsList($news_id): array {
        return NewsWidget::getPageWidgets($news_id);
    }
}

