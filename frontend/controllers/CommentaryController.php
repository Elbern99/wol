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
    
    protected function _getMainCategory() 
    {
        $category = Category::find()->where([
            'url_key' => 'commentary',
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
    
    public function _getVideosList($limit = null)
    {
        $ids = CommentaryVideo::videosListIds();

        return Video::find()
                        ->orderBy('id desc')
                        ->where('id IN('.$ids.')')
                        ->limit($limit)
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
        
        $opinionsQuery = Opinion::find()->orderBy('id desc');
        $ids = CommentaryVideo::videosListIds();
        $videosQuery = Video::find()->orderBy('id desc')->where('id IN('.$ids.')');
        
        return $this->render('index', [
            'opinions' => $this->_getOpinionsList($opinionLimit),
            'videos' => $this->_getVideosList($videoLimit),
            'category' => $this->_getMainCategory(),
            'opinionsCount' => $opinionsQuery->count(),
            'videosCount' => $videosQuery->count(),
            'opinionsSidebar' => $opinionsQuery->all(),
            'videosSidebar' => $videosQuery->all(),
            'opinionLimit' => $opinionLimit,
            'videoLimit' => $videoLimit,
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
        $videosQuery = Video::find()->orderBy('id desc')->where('id IN('.$ids.')');
        
        return $this->renderAjax('_videos', [
            'videos' => $this->_getVideosList($videoLimit),
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
           
        }
        else {
            return $this->redirect(['/commentary']);
        }
        
        $opinionsQuery = Opinion::find()->orderBy('id desc');
        
        return $this->renderAjax('_opinions', [
            'opinions' => $this->_getOpinionsList($opinionLimit),
            'opinionLimit' => $opinionLimit,
            'opinionsCount' => $opinionsQuery->count()
        ]);
         
    } 
    
    
    
}