<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Topic;
use common\models\Category;

/**
 * Site controller
 */
class TopicController extends Controller {
    
    protected function _getMainCategory() 
    {
        $category = Category::find()->where([
            'url_key' => 'key-topics',
        ])->one();
        return $category;
    }
    
    public function _getTopicsList($limit = null)
    {
        return Topic::find()
                        ->orderBy('id desc')
                        ->limit($limit)
                        ->all();
    }
    
    public function actionIndex()
    {
        $limit = Yii::$app->params['topic_limit'];

        if (Yii::$app->request->getIsPjax()) {
            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev;
            }

        }
        
        $topicsQuery = Topic::find()->orderBy('id desc');
        
        return $this->render('index', [
            'category' => $this->_getMainCategory(),
            'topics' => $this->_getTopicsList($limit),
            'topicsCount' => $topicsQuery->count(),
            'limit' => $limit,
        ]);
    }
}