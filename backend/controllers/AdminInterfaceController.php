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
use backend\models\AdminInterfaceVersions;
use backend\modules\parser\ParserFacade;
use \backend\models\ArchiveLog;

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
                        'actions' => [
                            'index', 'upload', 'versions', 
                            'upload-log', 'upload-log-delete', 'upload-log-view'
                        ],
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
    
    public function actionUploadLog() {
        
        $model = ArchiveLog::find()->orderBy(['created_at' => SORT_DESC]);
        return $this->render('upload-log', ['dataProvider' => new ActiveDataProvider(['query' => $model, 'pagination' => ['pageSize' => 50]])]);
    }
    
    public function actionUploadLogDelete($id) {
         try {
            $model = ArchiveLog::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }

            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'ArchiveLog was delete!'));
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'ArchiveLog did not delete!'));
        }

        return $this->redirect('@web/admin-interface/upload-log');
    }
    
    public function actionUploadLogView($id) {
        
        $model = ArchiveLog::findOne($id);
        
        if (!is_object($model)) {
            throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
        }
        
        return $this->render('upload-log-view', ['model' => $model]);
    }

    public function actionUpload() {

        $model = new AdminInterfaceUpload();

        if (Yii::$app->request->isPost) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            $model->load(Yii::$app->request->post());
            $model->initEvent();
            $model->initUploadProperty();
            
            if ($model->upload(true)) {
                
                $uploadLog = new ArchiveLog(); 
                        
                try {
                    
                    $facade = new ParserFacade($model);
                    $result = $facade->run();
                    
                    if ($result instanceof \common\contracts\LogInterface) {
                        
                        $uploadLog->addErrorLog($model->archive->name, $result->getLog());
                        Yii::$app->response->setStatusCode(400);
                        
                        return [
                            'files' => [
                                ['name' => $model->archive->name],
                            ],
                            'log' => $uploadLog->id
                        ];
                    }
                    
                } catch(\Exception $e) {
                    
                    $uploadLog->addErrorLog($model->archive->name, [$e->getMessage()]);
                    Yii::$app->response->setStatusCode(400);
                        
                    return [
                        'files' => [
                            ['name' => $model->archive->name,],
                        ],
                        'log' => $uploadLog->id
                    ];
                }
                
                $uploadLog->addSuccessLog($model->archive->name);
                
                return [
                    'files' => [
                        ['name' => $model->archive->name,],
                    ],
                ];

            } else {
                
                Yii::$app->response->setStatusCode(400);
                
                return [
                    'error' => Yii::t('app/text', 'Upload was not success')
                ];
            }
        }
        
        return $this->render('upload', ['model' => $model]);
    }
    
    public function actionVersions() {
        
        $model = new AdminInterfaceVersions();

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
        
        return $this->render('versions', ['model' => $model]);
    }
}
