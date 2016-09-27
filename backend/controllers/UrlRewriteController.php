<?php

namespace backend\controllers;

use Yii;
use common\models\UrlRewrite;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/*
 * Url Rewrite Manager Class Controller
 */
class UrlRewriteController extends Controller
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
        $menu = UrlRewrite::find()->orderBy('id');
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $menu, 'pagination' => ['pageSize' => 50]])]);
    }
    
    public function actionView($id = null) {
        
        if (is_null($id)) {
            $model = new UrlRewrite();
        } else {
            $model = UrlRewrite::findOne($id);
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Rewrite added success'), false);
                return $this->redirect('@web/url-rewrite');
            }
        }
        
        return $this->render('view', ['model'=>$model]);
    }
    
    public function actionDelete($id) {
        
        try {
            $model = UrlRewrite::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Rewrite was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Rewrite did not delete!'));
        }
             
        return $this->redirect('@web/url-rewrite');
    }
}
