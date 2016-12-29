<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Opinion;
use common\models\Category;
use common\models\Widget;
/**
 * Site controller
 */
class OpinionController extends Controller {
    
    protected function _getEventMainCategory() 
    {
        $category = Category::find()->where([
            'url_key' => 'events',
        ])->one();
        return $category;
    }
    
    public function actionIndex()
    {   
        $opinios = Opinion::find()->all();
        // var_dump($opinios);
        
        
        return $this->render('index', [
            'opinions' => $opinios,
            'category' => null,
        ]);
      //  return 'Opinion listing';
    }
    
    public function actionView($slug = null)
    {
        if (!$slug)
            return $this->goHome();
     
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