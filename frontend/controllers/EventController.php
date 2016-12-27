<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Event;

/**
 * Site controller
 */
class EventController extends Controller {
    
    public function actionIndex($month = null, $year = null)
    {   
        $groupsQuery = (new \yii\db\Query())
                ->select(['MONTH(date_from) as m', 'YEAR(date_from) as y'])
                ->from('events')
                ->groupBy(['MONTH(date_from)', 'YEAR(date_from)'])
                ->orderBy('MONTH(date_from) desc, YEAR(date_from) desc');
        
        $eventsTree = [];
        
        foreach ($groupsQuery->all() as $key => $value)
        {
            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
            $groupYear = (int)ArrayHelper::getValue($value, 'y');
           
            $eventsTree[$groupYear]['months'][] = [
                'isActive' => $groupMonth == $month ? true : false,
                'num' => $groupMonth,
            ];
            
            $eventsTree[$groupYear]['isActive'] = $groupYear == $year ? true : false;
        }

        if ($month && $year) {
           $groupsQuery->andWhere([
                'MONTH(date_from)' => $month,
                'YEAR(date_from)' => $year,
            ]);
        }
        else if (!$month && $year) {
            $groupsQuery->andWhere([
                'YEAR(date_from)' => $year,
            ]);
        }
        
        $groups = [];
        
        foreach ($groupsQuery->all() as $key => $value)
        {
            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
            $groupYear = (int)ArrayHelper::getValue($value, 'y');
            $yearMonth = $groupYear . '-' . $groupMonth;
            $groups[$yearMonth]['events'] = Event::find()->andWhere("date_from like '$yearMonth%'")->all();
            $groups[$yearMonth]['heading'] = date("F", mktime(0, 0, 0, $groupMonth, 10)) . ' ' . $groupYear;
            
        }
                  
        return $this->render('index', [
            'eventGroups' => $groups,
            'eventsTree' => $eventsTree,
        ]);
    }
    
    public function actionView($slug = null)
    {
        if (!$slug)
            return $this->goHome();
     
        $event = Event::find()->andWhere(['url_key' => $slug])->one();
        
        if (!$event) 
            return $this->redirect(Url::to(['/event/index']));
        
        $yearMonth = $event->date_from->format('Y-m');
        $otherEvents = Event::find()->andWhere("date_from like '$yearMonth%'")
                                    ->andWhere("id <> $event->id")
                                    ->all();
        
        $groupsQuery = (new \yii\db\Query())
                ->select(['MONTH(date_from) as m', 'YEAR(date_from) as y'])
                ->from('events')
                ->groupBy(['MONTH(date_from)', 'YEAR(date_from)'])
                ->orderBy('MONTH(date_from) desc, YEAR(date_from) desc');
        
        $eventsTree = [];
        
        foreach ($groupsQuery->all() as $key => $value)
        {
            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
            $groupYear = (int)ArrayHelper::getValue($value, 'y');
            $eventsTree[$groupYear][] = $groupMonth;
        }
        
        return $this->render('view', [
            'model' => $event,
            'otherEvents' => $otherEvents,
            'eventsTree' => $eventsTree,
        ]);
    }
}