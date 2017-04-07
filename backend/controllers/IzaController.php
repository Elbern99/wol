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
use backend\models\Article;
use yii\helpers\Url;
use common\modules\eav\Collection;
use common\modules\eav\StorageEav;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
                        'actions' => ['articles', 'authors', 'author-view', 'article-view'],
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
    
    public function actionAuthorView($id) {
        
        $author = AuthorSearch::findOne($id);
        $collection = Yii::createObject(Collection::class);
        $collection->initCollection('author', $author);
        
        return $this->render('collection', ['collection' => $collection, 'backLink' => Url::to('authors')]);
    }
    
    public function actionArticleView($id) {
        
        $eavFactory = new StorageEav();
        
        $article = Article::findOne($id);
        $collection = Yii::createObject(Collection::class);
        $attributes = $eavFactory->factory('type')->find()
                                 ->where(['name' => 'article'])
                                 ->with(['eavTypeAttributes.eavAttribute.eavAttributeOptions'])
                                 ->asArray()
                                 ->one();
        
        $collection->initCollection('article', $article, true);
        $values = $collection->getEntity()->getValues();
        $eavValuesModel = $eavFactory->factory('value');
        
        if (Yii::$app->request->isPost && $eavValuesModel->load(Yii::$app->request->post())) {
            $model = new \backend\models\EavValueManager($eavValuesModel, $values);
            echo '<pre>';
            var_dump($model->save());
            echo '</pre>';
            //exit;
        }

        $attributesData = [
            'type_id' => $attributes['id'],
            'model_id' => $id,
            'attributes' => []
        ];

        foreach ($attributes['eavTypeAttributes'] as $attribute) {

            if (isset($values[$attribute['eavAttribute']['name']])) {
                
                $value = $values[$attribute['eavAttribute']['name']];
                $originValue = $value->getOriginValue();

                if ($value->isMulti()) {
                    
                    foreach ($originValue as $data) {
                        
                        $v[$data->id] = [
                            'lang_id' => $data->lang_id,
                            'value' => unserialize($data->value)
                        ];
                    }

                } else {
                    $v[$originValue->id] = unserialize($originValue->value);
                }

                $attributesData['attributes'][$attribute['eavAttribute']['id']] = [
                    'name' => $attribute['eavAttribute']['name'],
                    'label' => $attribute['eavAttribute']['label'],
                    'required' => $attribute['eavAttribute']['required'],
                    'options' => array_map(function($value){
                        return ['label'=>$value['label'], 'type'=>$value['type']];
                    }, $attribute['eavAttribute']['eavAttributeOptions']),
                    'multi_lang_value' => $value->isMulti(),
                    'value' => $v
                ];
                
                unset($v);
            }

        }

        $this->getView()->registerJsFile('@web/js/eav_attributes_fields.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
  
        return $this->render('article-collection', ['articleModel' => $article, 'collection' => $attributesData]);
    }

}
