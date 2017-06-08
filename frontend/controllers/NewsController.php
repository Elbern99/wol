<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

use common\models\NewsItem;;
use common\models\NewsletterNews;

/**
 * Site controller
 */
class NewsController extends Controller {
    
    use \frontend\controllers\traits\NewsTrait;

    public function actionIndex($month = null, $year = null)
    {
        $limit = Yii::$app->params['news_limit'];

        if (Yii::$app->request->getIsPjax()) {
            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev - 1;
            }

        }
        
        $isInArchive = false;
        $newsQuery = NewsItem::find()->andWhere(['enabled' => 1])->orderBy('created_at desc');
        
        if ($month && $year) {
           $isInArchive = true;
           $newsQuery->andWhere([
                'MONTH(created_at)' => $month,
                'YEAR(created_at)' => $year,
            ]);
        } else if (!$month && $year) {
            $isInArchive = true;
            $newsQuery->andWhere([
                'YEAR(created_at)' => $year,
            ]);
        }

        return $this->render('index', [
            'news' => $this->getNewsList($limit, $year, $month),
            'newsCount' => $newsQuery->count(),
            'category' => $this->getMainCategory(),
            'widgets' => $this->getNewsWidgets(),
            'limit' => $limit,
            'isInArchive' => $isInArchive,
            'newsletterArchive' => $this->getNewsletterArchive(), 
            'latestArticles' => $this->getLastArticlesArchive(10),
            'newsArchive' => $this->getNewsArchive()
        ]);
    }
    
    public function actionView($slug)
    {
        $newsItem = NewsItem::find()->andWhere(['url_key' => $slug])->andWhere(['enabled' => 1])->one();
        
        if (!$newsItem) {
            throw new NotFoundHttpException();
        }

        $latestNews = NewsItem::find()->andWhere(['enabled' => 1])->orderBy(['id' => SORT_DESC])->limit(10)->all();
        $articles = $newsItem->getNewsArticles()->orderBy(['id' => SORT_DESC])->limit(10)->all();

        return $this->render('view', [
            'model' => $newsItem,
            'category' => $this->getMainCategory(),
            'newsSidebar' => $latestNews,
            'newsletterArchive' => $this->getNewsletterArchive(),
            'newsArchive' => $this->getNewsArchive(),
            'widgets' => $this->getNewsWidgetsList($newsItem->id),
            'articlesSidebar' => $articles,
        ]);
    }
    
    public function actionNewsletters($year, $month) {
        
        $urlKey = $year.'/'.$month;
        $model = NewsletterNews::find()->where(['url_key' => $urlKey])->one();
        
        if (!is_object($model)) {
            throw new NotFoundHttpException();
        }

        return $this->render('newsletter',[
            'model' => $model, 
            'newsletterArchive' => $this->getNewsletterArchive(), 
            'latestArticles' => $this->getLastArticlesArchive(10),
            'newsArchive' => $this->getNewsArchive(),
            'widgets' => $this->getNewsWidgets()
        ]);
    }
}