<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\NewsItem;
use common\models\Category;
use common\models\Widget;
use common\models\Article;
use common\models\NewsletterNews;

/**
 * Site controller
 */
class NewsController extends Controller {
    
    protected function _getMainCategory() 
    {
        $category = Category::find()->where([
            'url_key' => 'news',
        ])->one();
        return $category;
    }
    
    public function _getNewsList($limit = null, $year = null, $month = null)
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
    
    public function actionIndex($month = null, $year = null)
    {
        $limit = Yii::$app->params['news_limit'];

        if (Yii::$app->request->getIsPjax()) {
            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev;
            }

        }
        
        $widgets = Widget::find()->where([
            'name' => ['Subscribe to newsletter', 'Socials'],
        ])->orderBy('id desc')->all();
        
        
        $newsQuery = NewsItem::find()->orderBy('id desc');
        
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

        $groupsQuery = (new \yii\db\Query())
                ->select(['MONTH(created_at) as m', 'YEAR(created_at) as y'])
                ->from('news')
                ->groupBy(['MONTH(created_at)', 'YEAR(created_at)'])
                ->orderBy('MONTH(created_at) desc, YEAR(created_at) desc');
        
        $newsTree = [];
        
        foreach ($groupsQuery->all() as $key => $value)
        {
            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
            $groupYear = (int)ArrayHelper::getValue($value, 'y');
           
            $newsTree[$groupYear]['months'][] = [
                'isActive' => $groupMonth == $month ? true : false,
                'num' => $groupMonth,
            ];
            
            $newsTree[$groupYear]['isActive'] = $groupYear == $year ? true : false;
        }
        
        $articles = Article::find()->orderBy('id desc')
                                   ->limit(10)
                                   ->all();
        
        return $this->render('index', [
            'news' => $this->_getNewsList($limit, $year, $month),
            'newsCount' => $newsQuery->count(),
            'category' => $this->_getMainCategory(),
            'widgets' => $widgets,
            'newsTree' => $newsTree,
            'limit' => $limit,
            'articlesSidebar' => $articles,
        ]);
    }
    
    public function actionView($slug = null)
    {
        if (!$slug)
            return $this->goHome();
        
        $newsItem = NewsItem::find()->andWhere(['url_key' => $slug])->one();
        
        if (!$newsItem) 
            return $this->redirect(Url::to(['/news/index']));
        
        $widgets = Widget::find()->where([
            'name' => ['Subscribe to newsletter', 'Socials'],
        ])->orderBy('id desc')->all();
        
        $latestNews = NewsItem::find()->orderBy('id desc')->limit(10)->all();
        
        $groupsQuery = (new \yii\db\Query())
                ->select(['MONTH(created_at) as m', 'YEAR(created_at) as y'])
                ->from('news')
                ->groupBy(['MONTH(created_at)', 'YEAR(created_at)'])
                ->orderBy('MONTH(created_at) desc, YEAR(created_at) desc');
        
        $newsTree = [];
        
        foreach ($groupsQuery->all() as $key => $value)
        {
            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
            $groupYear = (int)ArrayHelper::getValue($value, 'y');
           
            $newsTree[$groupYear]['months'][] = [
                'isActive' => false,
                'num' => $groupMonth,
            ];
            
            $newsTree[$groupYear]['isActive'] = false;
        }
        
        $articles = Article::find()->orderBy('id desc')
                                   ->limit(10)
                                   ->all();
        return $this->render('view', [
            'model' => $newsItem,
            'category' => $this->_getMainCategory(),
            'widgets' => $widgets,
            'newsSidebar' => $latestNews,
            'newsTree' => $newsTree,
            'articlesSidebar' => $articles,
        ]);
//        if (!$slug)
//            return $this->goHome();
//     
//        $event = Event::find()->andWhere(['url_key' => $slug])->one();
//        
//        if (!$event) 
//            return $this->redirect(Url::to(['/event/index']));
//        
//        $yearMonth = $event->date_from->format('Y-m');
//        $otherEvents = Event::find()->andWhere("date_from like '$yearMonth%'")
//                                    ->andWhere("id <> $event->id")
//                                    ->all();
//        
//        $groupsQuery = (new \yii\db\Query())
//                ->select(['MONTH(date_from) as m', 'YEAR(date_from) as y'])
//                ->from('events')
//                ->groupBy(['MONTH(date_from)', 'YEAR(date_from)'])
//                ->orderBy('MONTH(date_from) desc, YEAR(date_from) desc');
//        
//        $eventsTree = [];
//        $groups = [];
//        
//        foreach ($groupsQuery->all() as $key => $value)
//        {
//            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
//            $groupYear = (int)ArrayHelper::getValue($value, 'y');
//            
//            if (strlen($groupMonth) == 1) {
//                $groupMonth = '0'.$groupMonth;
//            }
//            
//            $eventsTree[$groupYear][] = $groupMonth;
//            $yearMonth = $groupYear . '-' . $groupMonth;
//            $groups[$yearMonth]['events'] = Event::find()->andWhere("date_from like '$yearMonth%'")->all();
//            $groups[$yearMonth]['heading'] = date("F", mktime(0, 0, 0, $groupMonth, 10)) . ' ' . $groupYear;
//        }
//        
//        $widgets = Widget::find()->where([
//            'name' => ['event_widget1', 'event_widget2'],
//        ])->all();
//        
//        
//        return $this->render('view', [
//            'model' => $event,
//            'otherEvents' => $otherEvents,
//            'eventsTree' => $eventsTree,
//            'category' => $this->_getEventMainCategory(),
//            'widgets' => $widgets,
//            'eventGroups' => $groups,
//        ]);
    }
    
    public function actionNewsletters($year, $month) {
        
        $urlKey = $year.'/'.$month;
        $model = NewsletterNews::find()->where(['url_key' => $urlKey])->one();
        
        if (!is_object($model)) {
            throw new NotFoundHttpException();
        }
        
        return $this->render('newsletter',['model'=>$model]);
    }
}