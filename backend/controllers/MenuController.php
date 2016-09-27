<?php

namespace backend\controllers;

use Yii;
use common\models\BottomMenu;
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
                        'actions' => ['bottom', 'bottom-view', 'bottom-delete'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'bottom-delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionBottom() {
        $menu = BottomMenu::find()->orderBy('order');
        return $this->render('bottom/index', ['dataProvider' => new ActiveDataProvider(['query' => $menu, 'pagination' => ['pageSize' => 20]])]);
    }
    
    public function actionBottomView($id = null) {
        
        if (is_null($id)) {
            $model = new BottomMenu();
        } else {
            $model = BottomMenu::findOne($id);
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Link added success'), false);
                return $this->redirect('@web/menu/bottom');
            }
        }
        
        return $this->render('bottom/view', ['model'=>$model]);
    }
    
    public function actionBottomDelete($id) {
        
        try {
            $model = BottomMenu::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Link was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Link did not delete!'));
        }
             
        return $this->redirect('@web/menu/bottom');
    }
}
