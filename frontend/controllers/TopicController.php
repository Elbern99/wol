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
        $query = (new \yii\db\Query())
        ->select('*')
        ->from('topics')
        ->where('sticky_at is null')
        ->orderBy('created_at desc');
        
        $topics = Topic::find()
                            ->where('sticky_at is not null')
                            ->orderBy('sticky_at asc')
                            ->union($query)
                            ->limit($limit)
                            ->all();
        
        return array_slice($topics, 0, $limit);
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