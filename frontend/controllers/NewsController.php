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
                $limit += (int)$limitPrev - 1;
            }

        }
        
        $widgets = Widget::find()->where([
            'name' => ['stay_up_to_date', 'Socials'],
        ])->orderBy('id desc')->all();
        
        $isInArchive = false;
        $newsQuery = NewsItem::find()->orderBy('created_at desc');
        
        if ($month && $year) {
           $isInArchive = true;
           $newsQuery->andWhere([
                'MONTH(created_at)' => $month,
                'YEAR(created_at)' => $year,
            ]);
        }
        else if (!$month && $year) {
            $isInArchive = true;
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
                'isActive' => $groupMonth == $month && $groupYear == $year ? true : false,
                'num' => $groupMonth,
            ];
            
            $newsTree[$groupYear]['isActive'] = $groupYear == $year ? true : false;
        }
        
        $articles = Article::find()->orderBy('id desc')
                                   ->limit(10)
                                   ->all();
        
        krsort($newsTree, SORT_NUMERIC);
        
        $newsletterArchive = NewsletterNews::find()->select(['title', 'date', 'url_key'])->orderBy(['date' => SORT_DESC])->all();
        
        return $this->render('index', [
            'news' => $this->_getNewsList($limit, $year, $month),
            'newsCount' => $newsQuery->count(),
            'category' => $this->_getMainCategory(),
            'widgets' => $widgets,
            'newsTree' => $newsTree,
            'limit' => $limit,
            'articlesSidebar' => $articles,
            'newsletterArchive' => $newsletterArchive,
            'isInArchive' => $isInArchive,
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
            'name' => ['stay_up_to_date', 'Socials'],
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
        
        $articles = $newsItem->getNewsArticles()
                ->orderBy('id desc')
                ->limit(10)->all();
        
        krsort($newsTree, SORT_NUMERIC);
        $newsletterArchive = NewsletterNews::find()->select(['title', 'date', 'url_key'])->orderBy(['date' => SORT_DESC])->all();
        
        return $this->render('view', [
            'model' => $newsItem,
            'category' => $this->_getMainCategory(),
            'widgets' => $widgets,
            'newsSidebar' => $latestNews,
            'newsTree' => $newsTree,
            'articlesSidebar' => $articles,
            'newsletterArchive' => $newsletterArchive,
        ]);
    }
    
    public function actionNewsletters($year, $month) {
        
        $urlKey = $year.'/'.$month;
        $model = NewsletterNews::find()->where(['url_key' => $urlKey])->one();
        
        if (!is_object($model)) {
            throw new NotFoundHttpException();
        }
        
        $newsletterArchive = NewsletterNews::find()->select(['title', 'date', 'url_key'])->orderBy(['date' => SORT_DESC])->all();
        
        return $this->render('newsletter',['model'=>$model, 'newsletterArchive' => $newsletterArchive]);
    }
}