<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\ArticleSearch;
use backend\models\AuthorSearch;
use yii\helpers\Url;

/*
 * Article Author Class Controller
 */
class IzaController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['articles', 'authors'],
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

    public function actionArticles() {
        
        if (Yii::$app->request->getIsAjax()) {
            $this->changeEnabledAjax(ArticleSearch::class);
        }
        
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->getView()->registerJsFile(Url::to(['/js/field-ajax-change.js']), ['depends' => [\yii\web\JqueryAsset::className()]]);
        
        return $this->render('articles', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    protected function changeEnabledAjax($class) {
        
        $post = Yii::$app->request->post();

        if (isset($post['id'])) {
            $model = $class::findOne($post['id']);
            $model->enabled = ($post['enabled'] === 'true') ? 1 : 0;

            if ($model->save()) {
                return true;
            }
        }

        return false;
    }
    
    public function actionAuthors() {
        
        if (Yii::$app->request->getIsAjax()) {
            $this->changeEnabledAjax(AuthorSearch::class);
        }

        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->getView()->registerJsFile(Url::to(['/js/field-ajax-change.js']), ['depends' => [\yii\web\JqueryAsset::className()]]);
        
        return $this->render('authors', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
