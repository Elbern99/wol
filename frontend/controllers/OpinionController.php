<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Video;
use common\models\Widget;
use common\models\Opinion;
/**
 * Site controller
 */
class OpinionController extends Controller {

    use \frontend\controllers\traits\CommentaryTrait;
    
    private $mainCategory = 'opinions';
    
    public function actionIndex()
    {   
        $limit = Yii::$app->params['opinion_limit'];

        if (Yii::$app->request->getIsPjax()) {
            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev - 1;
            }
        }
        
        $opinions = $this->getOpinionsList();
                    
        $videosQuery = Video::find()->orderBy('id desc');
        $widgets = Widget::find()->where([
            'name' => ['stay_up_to_date'],
        ])->all();

        return $this->render('index', [
            'opinions' => $this->getOpinionsList($limit),
            'category' => $this->getMainCategory($this->mainCategory),
            'opinionsCount' => count($opinions),
            'opinionsSidebar' => $opinions,
            'videosSidebar' => $videosQuery->all(),
            'widgets' => $widgets,
            'limit' => $limit,
        ]);
    }
    
    public function actionView($slug)
    {

        $opinion = Opinion::find()->andWhere(['url_key' => $slug])->andWhere(['enabled' => 1])
                    ->with(['opinionAuthors' => function($query) {
                            return $query->select(['opinion_id','author_name', 'author_url'])->orderBy('author_order')->asArray();
                   }])->one();
        
        if (!$opinion) {
            throw new NotFoundHttpException();
        }
        
        $widgets = Widget::find()->where([
            'name' => ['event_widget1', 'event_widget2'],
        ])->all();
        
        $videosSidebar = Video::find()->orderBy('id desc')->all();
        $opinion->renderTwitterTags();
        
        return $this->render('view', [
            'model' => $opinion,
            'category' => $this->getMainCategory($this->mainCategory),
            'widgets' => $widgets,
            'opinionsSidebar' => $this->getOpinionsList(),
            'videosSidebar' => $videosSidebar,
        ]);
    }
}