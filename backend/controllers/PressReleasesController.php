<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use common\models\PressReleaseItem;


/*
 * Opinion Manager Class Controller
 */
class PressReleasesController extends Controller
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
        $opinionsQuery = PressReleaseItem::find()->orderBy('id desc');
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $opinionsQuery, 'pagination' => ['pageSize' => 30]])]);
    }
    
    public function actionView($id = null) {
        
        if (is_null($id)) {
            $model = new PressReleaseItem();
        } else {
            $model = PressReleaseItem::findOne($id);
            $model->created_at = $model->created_at->format('d-m-Y');
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->saveFormatted()) {
                if (!$id) {
                    $message = Yii::t('app/text', 'Press Release item has been added successfully.');
                    $path = '@web/press-releases';
                }
                else {
                    $message = Yii::t('app/text', 'Press Release item has been updated successfully.');
                    $path = ['/press-releases/view', 'id' => $id];
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
            $model = PressReleaseItem::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Press Release has been deleted successfully.'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'An error occurred during deletion.'));
        }
             
        return $this->redirect('@web/press-releases');
    }
    
    public function actionDeleteImage() {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            try {
                $id = Yii::$app->request->post('id');
                $model = PressReleaseItem::findOne($id);
                if (!is_object($model)) {
                    throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
                }
                $model->deleteImage();
                return Yii::t('app/text', 'Image has been deleted successfully.');

            } catch (\yii\db\Exception $e) {
                return Yii::t('app/text', 'An error occurred during deletion.');
            }
             
       
        } else {
            return $this->redirect('@web/press-releases');
        }
    }
}
