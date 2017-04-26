<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\PressReleaseItem;
use common\models\Category;
use common\models\Widget;
use common\models\Article;

/**
 * Site controller
 */
class PressReleasesController extends Controller {
    
    protected function _getMainCategory() 
    {
        $category = Category::find()->where([
            'url_key' => 'press-releases',
        ])->one();
        return $category;
    }
    
    public function _getPressReleasesList($limit = null, $year = null, $month = null)
    {
        $newsQuery = PressReleaseItem::find()->orderBy('created_at desc');
        
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
        
        
        $newsQuery = PressReleaseItem::find()->orderBy('created_at desc');
        
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
                ->from('press_releases')
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
        
        
        krsort($newsTree, SORT_NUMERIC);
        
        return $this->render('index', [
            'news' => $this->_getPressReleasesList($limit, $year, $month),
            'newsCount' => $newsQuery->count(),
            'category' => $this->_getMainCategory(),
            'widgets' => $widgets,
            'newsTree' => $newsTree,
            'limit' => $limit,
        ]);
    }
    
    public function actionView($slug = null)
    {
        if (!$slug)
            return $this->goHome();
        
        $newsItem = PressReleaseItem::find()->andWhere(['url_key' => $slug])->one();
        
        if (!$newsItem) 
            return $this->redirect(Url::to(['/press-releases/index']));
        
        $widgets = Widget::find()->where([
            'name' => ['stay_up_to_date', 'Socials'],
        ])->orderBy('id desc')->all();
        
        $latestNews = PressReleaseItem::find()->orderBy('created_at desc')->limit(10)->all();
        
        $groupsQuery = (new \yii\db\Query())
                ->select(['MONTH(created_at) as m', 'YEAR(created_at) as y'])
                ->from('press_releases')
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
        
        
        krsort($newsTree, SORT_NUMERIC);
        
        return $this->render('view', [
            'model' => $newsItem,
            'category' => $this->_getMainCategory(),
            'widgets' => $widgets,
            'newsSidebar' => $latestNews,
            'newsTree' => $newsTree,
        ]);
    }
    
}