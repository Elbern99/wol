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
use common\helpers\VideoHelper;

/**
 * Site controller
 */
class VideoController extends Controller {
    
    protected function _getMainCategory() 
    {
        $category = Category::find()->where([
            'url_key' => 'videos',
        ])->one();
        return $category;
    }
    
    
    public function _getVideosList($limit = null)
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
        
        $opinionsQuery = Opinion::find()->orderBy('id desc');
        $videosQuery = Video::find()->orderBy('id desc');
        $widgets = Widget::find()->where([
            'name' => ['Subscribe to newsletter'],
        ])->all();

        
        return $this->render('index', [
            'videos' => $this->_getVideosList($limit),
            'category' => $this->_getMainCategory(),
            'videosCount' => $videosQuery->count(),
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
        
        $video = Video::find()->andWhere(['url_key' => $slug])->one();
        
        if (!$video)
            return $this->redirect(Url::to(['/video/index']));
        
        $widgets = Widget::find()->where([
            'name' => ['event_widget1', 'event_widget2'],
        ])->all();
        
        $videosSidebar = Video::find()->orderBy('id desc')->all();
        $opinionsSidebar = Opinion::find()->orderBy('id desc')->all();
        
        return $this->render('view', [
            'model' => $video,
            'category' => $this->_getMainCategory(),
            'widgets' => $widgets,
            'opinionsSidebar' => $opinionsSidebar,
            'videosSidebar' => $videosSidebar,
        ]);
    }
    
}