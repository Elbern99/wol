<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use common\models\Event;


/*
 * Event Manager Class Controller
 */
class EventController extends Controller
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
        $eventsQuery = Event::find()->orderBy('id desc');
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $eventsQuery, 'pagination' => ['pageSize' => 30]])]);
    }
    
    public function actionView($id = null) {
        
        if (is_null($id)) {
            $model = new Event();
        } else {
            $model = Event::findOne($id);
            $model->date_from = $model->date_from->format('d-m-Y');
            $model->date_to = $model->date_to->format('d-m-Y');
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->saveFormatted()) {
                if (!$id) {
                    $message = Yii::t('app/text', 'Event has been added successfully.');
                }
                else {
                    $message = Yii::t('app/text', 'Event has been updated successfully.');
                }
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', $message), false);
                return $this->redirect('@web/event');
            }
            else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/text', implode('\n', $model->getFirstErrors())), false);
            }
       }
        
        return $this->render('view', ['model'=> $model]);
    }
    
    public function actionDelete($id) {
        
        try {
            $model = Event::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Event has been deleted successfully.'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'An error occurred during deletion.'));
        }
             
        return $this->redirect('@web/event');
    }
    
    public function actionDeleteImage() {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            try {
                $id = Yii::$app->request->post('id');
                $model = Event::findOne($id);
                if (!is_object($model)) {
                    throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
                }
                $model->deleteImage();
                return Yii::t('app/text', 'Image has been deleted successfully.');

            } catch (\yii\db\Exception $e) {
                return Yii::t('app/text', 'An error occurred during deletion.');
            }
             
       
        } else {
            return $this->redirect('@web/event');
        }
    }
}
