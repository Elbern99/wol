<?php

namespace backend\controllers;

use Yii;
use common\models\Video;
use common\models\CommentaryVideo;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/*
 * Video Manager Class Controller
 */
class VideoController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'delete', 'commentary'],
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
        $video = Video::find()->orderBy('order');
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $video, 'pagination' => ['pageSize' => 30]])]);
    }
    
    public function actionView($id = null) {
        
        if (is_null($id)) {
            $model = new Video();
        } else {
            $model = Video::findOne($id);
            $model->loadAttributes();
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveData()) {
                if (!$id) {
                    $message = Yii::t('app/text', 'Video has been added successfully.');
                    $path = '@web/video';
                }
                else {
                    $message = Yii::t('app/text', 'Video has been updated successfully.');
                    $path = ['/video/view', 'id' => $id];
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
            $model = Video::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Video has been deleted successfully.'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'An error occurred during deletion.'));
        }
             
        return $this->redirect('@web/video');
    }
    
    public function actionCommentary() {
        
        $model = new CommentaryVideo();
        $model->loadAttributes();
        
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveData()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Videos listing has been updated successfully.'), false);
                return $this->redirect('@web/video/commentary');
            }
        }
        
        return $this->render('commentary', [
            'model' => $model, 
        ]);
    }
}
