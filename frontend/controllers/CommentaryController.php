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
use common\models\CommentaryVideo;
/**
 * Site controller
 */
class CommentaryController extends Controller {
    
    use \frontend\controllers\traits\CommentaryTrait;
    
    private $mainCategory = 'commentary';
    
    protected function getVideosList($limit = null)
    {
        $ids = CommentaryVideo::videosListIds();
        $video = Video::find();
        
        if ($ids) {
            $video->where(['id' => $ids]);
        }
        
        return  $video->limit($limit)
                    ->orderBy(['id' => SORT_DESC])
                    ->all();
        
    }
    
    public function actionIndex()
    {   
        $opinionLimit = Yii::$app->params['opinion_limit'];
        $videoLimit =  Yii::$app->params['video_limit'];

        if (Yii::$app->request->getIsPjax()) {
            if (Yii::$app->request->get('opinion_limit')) {
                $limitPrev = Yii::$app->request->get('opinion_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $opinionLimit += (int)$limitPrev;
                }
            }
            if (Yii::$app->request->get('video_limit')) {
                $limitPrev = Yii::$app->request->get('video_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $videoLimit += (int)$limitPrev;
                }
            }
           
        }
        
        $opinions = $this->getOpinionsList();
        $ids = CommentaryVideo::videosListIds();

        $hasVideo = false;
        
        if ($ids) {
            $videosQuery = Video::find()->where(['id' => $ids])->orderBy('id desc');
            $hasVideo = true;
        } else {
            $videosQuery = Video::find()->orderBy('id desc');
        }
        
        return $this->render('index', [
            'opinions' => $this->getOpinionsList($opinionLimit),
            'videos' => $this->getVideosList($videoLimit),
            'category' => $this->getMainCategory($this->mainCategory),
            'opinionsCount' => count($opinions),
            'videosCount' => $videosQuery->count(),
            'opinionsSidebar' => $opinions,
            'videosSidebar' => $videosQuery->all(),
            'opinionLimit' => $opinionLimit,
            'videoLimit' => $videoLimit,
            'hasVideo' => $hasVideo,
        ]);
    }

    
    public function actionVideos()
    {
        $videoLimit =  Yii::$app->params['video_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            if (Yii::$app->request->get('video_limit')) {
                $limitPrev = Yii::$app->request->get('video_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $videoLimit += (int)$limitPrev;
                }
            }
           
        }
        else {
            return $this->redirect(['/commentary']);
        }
        
        $ids = CommentaryVideo::videosListIds();
        $videosQuery = Video::find()->where(['id' => $ids])->orderBy('id desc');
        
        return $this->renderAjax('_videos', [
            'videos' => $this->getVideosList($videoLimit),
            'videoLimit' => $videoLimit,
            'videosCount' => $videosQuery->count()
        ]);
         
    }
    
    public function actionOpinions()
    {
        $opinionLimit =  Yii::$app->params['opinion_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            if (Yii::$app->request->get('opinion_limit')) {
                $limitPrev = Yii::$app->request->get('opinion_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $opinionLimit += (int)$limitPrev;
                }
            }
           
        } else {
            return $this->redirect(['/commentary']);
        }
        
        $opinions = $this->getOpinionsList();
        
        return $this->renderAjax('_opinions', [
            'opinions' => $this->getOpinionsList($opinionLimit),
            'opinionLimit' => $opinionLimit,
            'opinionsCount' => count($opinions)
        ]);
         
    } 
    
    
    
}