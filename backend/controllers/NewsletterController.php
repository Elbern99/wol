<?php

namespace backend\controllers;

use Yii;
use common\models\NewsletterNews;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use common\models\Newsletter;
use common\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

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
                        'actions' => ['news', 'news-view', 'news-delete', 'subscribers', 'subscriber-delete', 'subscribers-export'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'news-delete' => ['post'],
                    'subscriber-delete' => ['post']
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
    
    public function actionNewsDelete($id) {
        
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
    
    public function actionSubscribers() {
        $categories =  Category::find()
                            ->alias('s')
                            ->select(['c.id', 'c.title'])
                            ->innerJoin(Category::tableName().' AS c', 's.id = c.root')
                            ->where(['s.url_key' => 'articles', 'c.active' => 1, 'c.lvl' => 1])
                            ->asArray()
                            ->all();
        
        $areas = ArrayHelper::map($categories, 'id', 'title');
        
        $news = Newsletter::find()->orderBy('created_at');
        return $this->render('newsletter', ['dataProvider' => new ActiveDataProvider(['query' => $news, 'pagination' => ['pageSize' => 30]]), 'areas' => $areas]);
    }
    
    public function actionSubscriberDelete($id) {
        
        try {
            $model = Newsletter::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Subscriber was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Subscriber did not delete!'));
        }
             
        return $this->redirect('@web/newsletter/subscribers');
    }
    
    public function actionSubscribersExport() {
        
        $folderPath = Yii::$app->basePath.'/runtime/temporary_folder/export';
        
        if (!FileHelper::createDirectory($folderPath, 0775, true)) {
            throw new NotFoundHttpException(Yii::t('app/text','Folder can not be create.'));
        }
        
        $filePath = $folderPath.'/'.'subscribers.csv';

        $parent = Category::find()
                             ->where(['url_key' => 'articles'])
                             ->select(['root', 'lvl', 'lft', 'rgt'])
                             ->one();

        $categories =  $parent->children()
                            ->select(['id', 'taxonomy_code'])
                            ->asArray()
                            ->all();
        
        $categories = ArrayHelper::map($categories, 'id', 'taxonomy_code');
        $subscribers = Newsletter::find()->alias('n')
                                         ->select([
                                            'email', 'first_name', 'last_name', 
                                            'areas_interest', 'interest', 'iza_world', 
                                            'iza', 'user_id as registered'
                                         ])
                                         ->asArray()
                                         ->all();
        
        if (count($subscribers)) {
            
            $fp = fopen($filePath, 'w+');
            fputcsv($fp, ['email', 'first_name', 'last_name', 
                                            'areas_interest', 'interest', 'iza_world', 
                                            'iza', 'registered']);

            foreach ($subscribers as $fields) {
                
                $areas_interest = explode(',', $fields['areas_interest']);
                $areas_interest_taxonomy = [];
                
                foreach ($areas_interest as $ai) {
                    if (isset($categories[$ai])) {
                        $areas_interest_taxonomy[] = $categories[$ai];
                    }

                }
                
                $fields['areas_interest'] = implode(',', $areas_interest_taxonomy);
                
                if (is_null($fields['registered'])) {
                    $fields['registered'] = 0;
                } else {
                    $fields['registered'] = 1;
                }
                
                fputcsv($fp, $fields);
            }
            
            fclose($fp);
        }

        if (file_exists($filePath)) {
            return Yii::$app->getResponse()->sendContentAsFile(file_get_contents($filePath), 'subscribers.csv');
        }
        
        throw new NotFoundHttpException(Yii::t('app/text','File not exist.'));
    }
    
}
