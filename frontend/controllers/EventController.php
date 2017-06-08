<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Event;
use common\models\Category;
use common\models\Widget;
/**
 * Site controller
 */
class EventController extends Controller {
    
    protected function _getEventMainCategory() 
    {
        $category = Category::find()->where([
            'url_key' => 'events',
        ])->one();
        return $category;
    }
    
    public function actionIndex($month = null, $year = null)
    {   
        $widgets = Widget::find()->where([
            'name' => ['event_widget1', 'event_widget2'],
        ])->all();

        $isArchive = false;

        $groupsQuery = (new \yii\db\Query())
                ->select(['MONTH(date_from) as m', 'YEAR(date_from) as y'])
                ->from('events')
                ->groupBy(['MONTH(date_from)', 'YEAR(date_from)'])
                ->orderBy('MONTH(date_from) desc, YEAR(date_from) desc')
                ->andWhere(['enabled' => 1]);
        
        $treeQuery = (new \yii\db\Query())
                ->select(['MONTH(date_from) as m', 'YEAR(date_from) as y'])
                ->from('events')
                ->groupBy(['MONTH(date_from)', 'YEAR(date_from)'])
                ->orderBy('MONTH(date_from) desc, YEAR(date_from) desc')
                ->andWhere(['enabled' => 1]);
        
        $eventsTree = [];
        
        foreach ($treeQuery->andWhere('date_from < now()')->all() as $key => $value)
        {
            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
            $groupYear = (int)ArrayHelper::getValue($value, 'y');
           
            
            $eventsTree[$groupYear]['months'][] = [
                'isActive' => $groupMonth == $month && $groupYear == $year ? true : false,
                'num' => $groupMonth,
            ];
            
            $eventsTree[$groupYear]['isActive'] = $groupYear == $year ? true : false;
        }

        if ($month && $year) {
            $isArchive = true;
            $groupsQuery->andWhere([
                'MONTH(date_from)' => $month,
                'YEAR(date_from)' => $year,
            ]);
        }
        else if (!$month && $year) {
            $isArchive = true;
            $groupsQuery->andWhere([
                'YEAR(date_from)' => $year,
            ])->andWhere('date_from < now()');
        }
        
        if (!$month && !$year) {
            $groupsQuery->andWhere('date_from >= now()');
        }
        
        $groups = [];
        
        foreach ($groupsQuery->all() as $key => $value)
        {
            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
            $groupYear = (int)ArrayHelper::getValue($value, 'y');
            
            if (strlen($groupMonth) == 1) {
                $groupMonth = '0'.$groupMonth;
            }
            
            $yearMonth = $groupYear . '-' . $groupMonth;
            $groups[$yearMonth]['events'] = Event::find()->andWhere("date_from like '$yearMonth%'")->andWhere(['enabled' => 1])->orderBy('date_from asc')->all();
            
            $groups[$yearMonth]['heading'] = date("F", mktime(0, 0, 0, $groupMonth, 10)) . ' ' . $groupYear;
        }
        
        krsort($eventsTree, SORT_NUMERIC);
        ksort($groups);

        $upcomingEvents = [];

        if ($isArchive) {
            $upcomingEvents = Event::find()->where('date_from >= now()')->andWhere(['enabled' => 1])->orderBy('date_from asc')->all();
        }
        
        return $this->render('index', [
            'eventGroups' => $groups,
            'eventsTree' => $eventsTree,
            'category' => $this->_getEventMainCategory(),
            'widgets' => $widgets,
            'isArchive' => $isArchive,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
    
    public function actionView($slug = null)
    {
        if (!$slug)
            return $this->goHome();
     
        $event = Event::find()->andWhere(['url_key' => $slug])->andWhere(['enabled' => 1])->one();
        
        if (!$event) 
            return $this->redirect(Url::to(['/event/index']));
        
        $yearMonth = $event->date_from->format('Y-m-d');
        $otherEvents = Event::find()->andWhere("date_from >= now()")
                                    ->andWhere("id <> $event->id")
                                    ->andWhere(['enabled' => 1])
                                    ->orderBy('date_from asc')
                                    ->limit(3)
                                    ->all();
        
        $groupsQuery = (new \yii\db\Query())
                ->select(['MONTH(date_from) as m', 'YEAR(date_from) as y'])
                ->from('events')
                ->groupBy(['MONTH(date_from)', 'YEAR(date_from)'])
                ->orderBy('MONTH(date_from) desc, YEAR(date_from) desc')
                ->andWhere(['enabled' => 1]);
        
        $treeQuery = (new \yii\db\Query())
                ->select(['MONTH(date_from) as m', 'YEAR(date_from) as y'])
                ->from('events')
                ->groupBy(['MONTH(date_from)', 'YEAR(date_from)'])
                ->orderBy('MONTH(date_from) desc, YEAR(date_from) desc')
                ->andWhere(['enabled' => 1]);
        
        $eventsTree = [];
        $groups = [];
        
        foreach ($treeQuery->andWhere('date_from < now()')->all() as $key => $value)
        {
            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
            $groupYear = (int)ArrayHelper::getValue($value, 'y');
            
            if (strlen($groupMonth) == 1) {
                $groupMonth = '0'.$groupMonth;
            }
            
            $eventsTree[$groupYear][] = $groupMonth;
        }
        
        foreach ($groupsQuery->andWhere('date_from >= now()')->all() as $key => $value)
        {
            $groupMonth = (int)ArrayHelper::getValue($value, 'm');
            $groupYear = (int)ArrayHelper::getValue($value, 'y');
            
            if (strlen($groupMonth) == 1) {
                $groupMonth = '0'.$groupMonth;
            }
            
            $yearMonth = $groupYear . '-' . $groupMonth;
            $groups[$yearMonth]['events'] = Event::find()->andWhere("date_from like '$yearMonth%'")->andWhere(['enabled' => 1])->orderBy('date_from asc')->all();
            $groups[$yearMonth]['heading'] = date("F", mktime(0, 0, 0, $groupMonth, 10)) . ' ' . $groupYear;
        }
        
        $widgets = Widget::find()->where([
            'name' => ['event_widget1', 'event_widget2'],
        ])->all();
        
        krsort($eventsTree, SORT_NUMERIC);
        ksort($groups);
        
        return $this->render('view', [
            'model' => $event,
            'otherEvents' => $otherEvents,
            'eventsTree' => $eventsTree,
            'category' => $this->_getEventMainCategory(),
            'widgets' => $widgets,
            'eventGroups' => $groups,
        ]);
    }
}