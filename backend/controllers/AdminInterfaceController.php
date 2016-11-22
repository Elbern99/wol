<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use backend\models\AdminInterfaceUpload;
use backend\modules\parser\ParserFacade;

/*
 * Article Author Class Controller
 */
class AdminInterfaceController extends Controller { 
    
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'upload'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionUpload() {

        $model = new AdminInterfaceUpload();

        if (Yii::$app->request->isPost) {

            $model->load(Yii::$app->request->post());
            $model->initUploadProperty();
            
            if ($model->upload(true)) {
                
                try {
                    
                    $facade = new ParserFacade($model);
                    $facade->run();
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Upload was success'), false);
                    
                } catch(\Exception $e) {
                    Yii::$app->getSession()->setFlash('error', $e->getMessage(), false);
                }
            
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Upload was not success'), false);
            }
        }
        
        return $this->render('upload', ['model' => $model]);
    }
}
