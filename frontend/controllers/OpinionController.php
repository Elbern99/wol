<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Opinion;
use common\models\Video;
use common\models\Category;
use common\models\Widget;
/**
 * Site controller
 */
class OpinionController extends Controller {
    
    protected function _getMainCategory() 
    {
        $category = Category::find()->where([
            'url_key' => 'opinions',
        ])->one();
        return $category;
    }
    
    
    public function _getOpinionsList($limit = null)
    {
        return Opinion::find()
                        ->orderBy('id desc')
                        ->limit($limit)
                        ->all();
    }
    
    
    public function actionIndex()
    {   
        $limit = Yii::$app->params['opinion_limit'];

        if (Yii::$app->request->getIsPjax()) {

            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev;
            }

        }
        
        $opinionsQuery = Opinion::find()->orderBy('id desc');
        $videosQuery = Video::find()->orderBy('id desc');
        $widgets = Widget::find()->where([
            'name' => ['Subscribe to newsletter'],
        ])->all();
        
        return $this->render('index', [
            'opinions' => $this->_getOpinionsList($limit),
            'category' => $this->_getMainCategory(),
            'opinionsCount' => $opinionsQuery->count(),
            'opinionsSidebar' => $opinionsQuery->all(),
            'videosSidebar' => $videosQuery->all(),
            'widgets' => $widgets,
            'limit' => $limit,
        ]);
    }
    
    public function actionView($slug = null)
    {
        if (!$slug)
            return $this->goHome();
        
        $opinion = Opinion::find()->andWhere(['url_key' => $slug])->one();
        
        if (!$opinion)
            return $this->redirect(Url::to(['/opinion/index']));
        
        $widgets = Widget::find()->where([
            'name' => ['event_widget1', 'event_widget2'],
        ])->all();
        
        $videosSidebar = Video::find()->orderBy('id desc')->all();
        $opinionsSidebar = Opinion::find()->orderBy('id desc')->all();
        
        return $this->render('view', [
            'model' => $opinion,
            'category' => $this->_getMainCategory(),
            'widgets' => $widgets,
            'opinionsSidebar' => $opinionsSidebar,
            'videosSidebar' => $videosSidebar,
        ]);
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
}