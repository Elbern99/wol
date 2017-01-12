<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use common\models\NewsItem;


/*
 * Opinion Manager Class Controller
 */
class NewsController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'delete'],
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
        $opinionsQuery = NewsItem::find()->orderBy('id desc');
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $opinionsQuery, 'pagination' => ['pageSize' => 30]])]);
    }
    
    public function actionView($id = null) {
        
        if (is_null($id)) {
            $model = new NewsItem();
        } else {
            $model = NewsItem::findOne($id);
            $model->created_at = $model->created_at->format('d-m-Y');
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->saveFormatted()) {
                if (!$id) {
                    $message = Yii::t('app/text', 'News item has been added successfully.');
                    $path = '@web/news';
                }
                else {
                    $message = Yii::t('app/text', 'News item has been updated successfully.');
                    $path = ['/news/view', 'id' => $id];
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
            $model = NewsItem::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'News item has been deleted successfully.'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'An error occurred during deletion.'));
        }
             
        return $this->redirect('@web/news');
    }
}
