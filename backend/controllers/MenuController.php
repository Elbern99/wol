<?php

namespace backend\controllers;

use Yii;
use common\models\MenuLinks;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/*
 * Menu Manager Class Controller
 */
class MenuController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['links', 'link-view', 'link-delete'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'link-delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionLinks() {
        $menu = MenuLinks::find()->orderBy('order');
        return $this->render('links/index', ['dataProvider' => new ActiveDataProvider(['query' => $menu, 'pagination' => ['pageSize' => 20]])]);
    }
    
    public function actionLinkView($id = null) {
        
        if (is_null($id)) {
            $model = new MenuLinks();
        } else {
            $model = MenuLinks::findOne($id);
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Link added success'), false);
                return $this->redirect('@web/menu/links');
            }
        }
        
        return $this->render('links/view', ['model'=>$model]);
    }
    
    public function actionLinkDelete($id) {
        
        try {
            
            $model = MenuLinks::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Link was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Link did not delete!'));
        }
             
        return $this->redirect('@web/menu/links');
    }
}
