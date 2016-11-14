<?php

namespace backend\controllers;

use Yii;
use common\models\Widget;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/*
 * Widget Manager Class Controller
 */
class WidgetController extends Controller {

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
        
        $model = Widget::find()->orderBy('id');
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $model, 'pagination' => ['pageSize' => 30]])]);
    }

    public function actionView($id = null) {

        if (is_null($id)) {
            $model = new Widget();
        } else {
            $model = Widget::findOne($id);
        }

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Widget added success'), false);
                return $this->redirect('@web/widget');
            }
        }

        return $this->render('view', ['model' => $model]);
    }

    public function actionDelete($id) {

        try {
            $model = Widget::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }

            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Widget was delete success!'));
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Widget did not delete!'));
        }

        return $this->redirect('@web/widget');
    }

}
