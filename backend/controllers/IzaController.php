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
use common\modules\eav\Collection;
use common\models\SynonymsSearch;

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
                        'actions' => ['articles', 'authors', 'author-view', 'article-view', 'synonyms', 'synonym-view', 'synonym-delete'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['synonym-delete'],
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
    
    public function actionAuthorView($id) {
        
        $author = AuthorSearch::findOne($id);
        $collection = Yii::createObject(Collection::class);
        $collection->initCollection('author', $author);
        
        return $this->render('collection', ['collection' => $collection, 'backLink' => Url::to('authors')]);
    }
    
    public function actionArticleView($id) {
        
        $article = ArticleSearch::findOne($id);
        $collection = Yii::createObject(Collection::class);
        $collection->initCollection('article', $article);
        
        return $this->render('collection', ['collection' => $collection, 'backLink' => Url::to('articles')]);
    }
    
    public function actionSynonyms() {
        
        $model = SynonymsSearch::find()->orderBy('id');
        return $this->render('synonyms', ['dataProvider' => new ActiveDataProvider(['query' => $model, 'pagination' => ['pageSize' => 30]])]);
    }
    
    public function actionSynonymView($id = null) {

        if (is_null($id)) {
            $model = new SynonymsSearch();
        } else {
            $model = SynonymsSearch::findOne($id);
        }

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            
            $model->convertToString();
            
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Synonym added success'), false);
                return $this->redirect('@web/iza/synonyms');
            }
        }

        return $this->render('synonyms-view', ['model' => $model]);
    }

    public function actionSynonymDelete($id) {

        try {
            
            $model = SynonymsSearch::findOne($id);
            
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }

            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Synonym was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Synonym did not delete!'));
        }

        return $this->redirect('@web/iza/synonyms');
    }

}
