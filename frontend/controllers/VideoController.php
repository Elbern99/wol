<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

use common\models\Video;
use common\models\Widget;

/**
 * Site controller
 */
class VideoController extends Controller {
    
    use \frontend\controllers\traits\CommentaryTrait;
    
    private $mainCategory = 'videos';
    
    protected function getVideosList($limit = null)
    {
        return Video::find()
                        ->orderBy('id desc')
                        ->limit($limit)
                        ->all();
    }

    public function actionIndex()
    {   
        $limit = Yii::$app->params['video_limit'];

        if (Yii::$app->request->getIsPjax()) {
            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev;
            }

        }

        $videosQuery = Video::find()->orderBy('id desc');
        $widgets = Widget::find()->where([
            'name' => ['stay_up_to_date'],
        ])->all();

        return $this->render('index', [
            'videos' => $this->getVideosList($limit),
            'category' => $this->getMainCategory($this->mainCategory),
            'videosCount' => $videosQuery->count(),
            'opinionsSidebar' => $this->getOpinionsList(),
            'videosSidebar' => $videosQuery->all(),
            'widgets' => $widgets,
            'limit' => $limit,
        ]);
    }
    
    public function actionView($slug)
    {
        $video = Video::find()->andWhere(['url_key' => $slug])->one();
        
        if (!$video) {
            throw new NotFoundHttpException();
        }
        
        $widgets = Widget::find()->where([
            'name' => ['event_widget1', 'event_widget2'],
        ])->all();
        
        $videosSidebar = Video::find()->orderBy('id desc')->all();
        $video->renderTwitterTags();
        $video->renderOgTags();

        return $this->render('view', [
            'model' => $video,
            'category' => $this->getMainCategory($this->mainCategory),
            'widgets' => $widgets,
            'opinionsSidebar' => $this->getOpinionsList(),
            'videosSidebar' => $videosSidebar,
        ]);
    }
    
}