<?php

namespace backend\controllers;

use Yii;
use common\models\NewsletterNews;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/*
 * Newslletter Manager Class Controller
 */
class NewsletterController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['news', 'news-view', 'news-delete'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'news-delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionNews() {
        $news = NewsletterNews::find()->orderBy('date');
        return $this->render('news', ['dataProvider' => new ActiveDataProvider(['query' => $news, 'pagination' => ['pageSize' => 30]])]);
    }
    
    public function actionNewsView($id = null) {
        
        if (is_null($id)) {
            $model = new NewsletterNews();
        } else {
            $model = NewsletterNews::findOne($id);
        }
        $model->validate();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'News added success'), false);
                return $this->redirect('@web/newsletter/news');
            }
        }
        
        return $this->render('news-view', ['model'=>$model]);
    }
    
    public function actionDelete($id) {
        
        try {
            $model = NewsletterNews::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','News was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','News did not delete!'));
        }
             
        return $this->redirect('@web/newsletter/news');
    }
}
