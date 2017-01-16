<?php

namespace backend\controllers;

use Yii;
use common\models\Video;
use common\models\Opinion;
use common\models\HomepageCommentary;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/*
 * Video Manager Class Controller
 */
class HomepageController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['commentary'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }
    
    public function actionCommentary() {
        
        $model = new HomepageCommentary();
        $model->loadAttributes();
        
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveData()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Homepage Commentary section has been updated successfully.'), false);
                return $this->redirect('@web/homepage/commentary');
            }
        }
        
        return $this->render('commentary', [
            'model' => $model, 
        ]);
    }
 
}
