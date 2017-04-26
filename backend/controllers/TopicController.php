<?php

namespace backend\controllers;

use Yii;
use common\models\Topic;


use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

class TopicController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'delete', 'delete-image'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex() {
        $topic = Topic::find()->orderBy('id desc');
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $topic, 'pagination' => ['pageSize' => 30]])]);
    }
    
    public function actionView($id = null) {
        
        if (is_null($id)) {
            $model = new Topic();
        } else {
            $model = Topic::findOne($id);
            $model->loadAttributes();
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveFormatted()) {
                if (!$id) {
                    $message = Yii::t('app/text', 'Topic has been added successfully.');
                    $path = '@web/topic';
                }
                else {
                    $message = Yii::t('app/text', 'Topic has been updated successfully.');
                    $path = ['/topic/view', 'id' => $id];
                }
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', $message), false);
                return $this->redirect($path);
            }
            else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/text', implode('\n', $model->getFirstErrors())), false);
            }
        }
        
        return $this->render('view', ['model'=> $model]);
    }
    
    public function actionDelete($id) {
        
        try {
            $model = Topic::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }
            
            if ($model->delete()) {

                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Topic has been deleted successfully.'));

            }
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'An error occurred during deletion.'));
        }
             
        return $this->redirect('@web/topic');
    }
    
    public function actionDeleteImage() {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            try {
                $id = Yii::$app->request->post('id');
                $model = Topic::findOne($id);
                if (!is_object($model)) {
                    throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
                }
                $model->deleteImage();
                return Yii::t('app/text', 'Image has been deleted successfully.');

            } catch (\yii\db\Exception $e) {
                return Yii::t('app/text', 'An error occurred during deletion.');
            }
             
       
        } else {
            return $this->redirect('@web/news');
        }
    }
}
