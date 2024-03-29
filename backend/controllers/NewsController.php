<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\NewsItem;
use backend\models\NewsSearch;
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
                        'actions' => ['index', 'view', 'delete', 'delete-image', 'news-widget'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'news-widget' => ['post'],
                ],
            ],
        ];
    }
    
    use traits\NewsWidgetTrait;
    
    public function actionIndex() {
        
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id = null) {
        
        if (is_null($id)) {
            $model = new NewsItem();
        } else {
            $model = NewsItem::findOne($id);
            $model->created_at = $model->created_at->format('d-m-Y');
            $model->loadAttributes();
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
        
        return $this->render('view', ['model'=> $model, 'widgets' => $this->getNewsWidget($model->id)]);
    }
    
    public function actionNewsWidget($id) {
        $this->changeWidget($id);
        return $this->redirect('@web/news/view?id='.$id);
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
    
    public function actionDeleteImage() {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            try {
                $id = Yii::$app->request->post('id');
                $model = NewsItem::findOne($id);
                if (!is_object($model)) {
                    throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
                }
                $model->deleteImage();
                return Yii::t('app/text', 'Image has been deleted successfully.');

            } catch (\yii\db\Exception $e) {
                return Yii::t('app/text', 'An error occurred during deletion.');
            }
             
       
        } else {
            return $this->redirect('@web/news');
        }
    }
}
