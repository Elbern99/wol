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
use common\models\SynonymsSearch;
use common\modules\eav\StorageEav;
use backend\models\Author;
use backend\models\UploadArticleFiles;

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
                        'actions' => [
                            'articles', 'authors', 'author-view', 
                            'article-view', 'synonyms', 'synonym-view', 
                            'synonym-delete', 'article-delete', 'author-delete'
                        ],
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
        
        $eavFactory = new StorageEav();
        
        $author = Author::findOne($id);
        $collection = Yii::createObject(Collection::class);
        $attributes = $eavFactory->factory('type')->find()
                                 ->where(['name' => 'author'])
                                 ->with(['eavTypeAttributes.eavAttribute.eavAttributeOptions'])
                                 ->asArray()
                                 ->one();
        
        $collection->initCollection('author', $author, true);
        $values = $collection->getEntity()->getValues();
        $eavValuesModel = $eavFactory->factory('value');
        
        if (Yii::$app->request->isPost) {
            
            if ($eavValuesModel->load(Yii::$app->request->post())) {
                
                $triggers = [
                    'model' => $author,
                    'attributes' => [
                        'name' => function($attributes, $model) {
                            $model->name = $attributes['first_name'].' '.$attributes['middle_name'].' '.$attributes['last_name'];
                            $model->surname = $attributes['last_name'];
                        }
                    ]
                ];
                
                $model = new \backend\models\EavValueManager($eavValuesModel, $values, $triggers);

                if ($model->save()) {
                     Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Author attributes update success!'));
                } else {
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Author attributes not update success!'));
                }

                return $this->redirect(Url::to(['iza/author-view', 'id' => $id]));

            } elseif ($author->load(Yii::$app->request->post())) {

                $author->initUploadProperty();
                
                if ($author->validate()) {

                    
                    if (!is_object($author->avatar)) {
                        $author->avatar = $author->getOldAttribute('avatar');
                    } else {
                        $author->upload(true);
                    }
                    
                    if ($author->save(false)) {
                         Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Author data update success!'));
                    } else {
                        Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Author data not update success!'));
                    }

                    return $this->redirect(Url::to(['iza/author-view', 'id' => $id]));
                }
            }
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
                $v[$originValue->id] = unserialize($originValue->value);
                
                $attributesData['attributes'][$attribute['eavAttribute']['id']] = [
                    'name' => $attribute['eavAttribute']['name'],
                    'label' => $attribute['eavAttribute']['label'],
                    'required' => $attribute['eavAttribute']['required'],
                    'options' => array_map(function($value){
                        return ['label'=>$value['label'], 'type'=>$value['type']];
                    }, $attribute['eavAttribute']['eavAttributeOptions']),
                    'multi_lang_value' => false,
                    'value' => $v
                ];
                
                unset($v);
            }

        }

        $this->getView()->registerJsFile('@web/js/eav_attributes_fields.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
  
        return $this->render('author-collection', ['authorModel' => $author, 'collection' => $attributesData]);
    }
    
    public function actionArticleView($id) {
        
        $eavFactory = new StorageEav();
        
        $article = Article::findOne($id);
        $fileUploadModel = new UploadArticleFiles([], $article);
        
        $collection = Yii::createObject(Collection::class);
        $attributes = $eavFactory->factory('type')->find()
                                 ->where(['name' => 'article'])
                                 ->with(['eavTypeAttributes.eavAttribute.eavAttributeOptions'])
                                 ->asArray()
                                 ->one();
        
        $collection->initCollection('article', $article, true);
        $values = $collection->getEntity()->getValues();
        $eavValuesModel = $eavFactory->factory('value');
        
        if (Yii::$app->request->isPost) {
            
            if ($eavValuesModel->load(Yii::$app->request->post())) {
                
                $triggers = [
                    'model' => $article,
                    'attributes' => [
                        'title' => function($attributes, $model) {
                    
                            if (!isset($attributes['lang']) || !$attributes['lang']) {
                                $model->title = $attributes['title'];
                            }
                        }
                    ]
                ];
                    
                $model = new \backend\models\EavValueManager($eavValuesModel, $values, $triggers);

                if ($model->save()) {
                     Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Article attributes update success!'));
                } else {
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Article attributes not update success!'));
                }
                
                return $this->redirect(Url::to(['iza/article-view', 'id' => $id]));
                
            } elseif ($article->load(Yii::$app->request->post())) {
                
                if ($article->save()) {
                     Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Article data update success!'));
                } else {
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Article data not update success!'));
                }
                
                return $this->redirect(Url::to(['iza/article-view', 'id' => $id]));
                
            } elseif ($fileUploadModel->load(Yii::$app->request->post())) {
                
                $fileUploadModel->initUploadProperty();
                $fileUploadModel->filename = ($fileUploadModel->filename) ? 
                        $fileUploadModel->filename.'.'.$fileUploadModel->file->extension : $fileUploadModel->file->name;

                if ($fileUploadModel->validate()) {
                    
                    if ($fileUploadModel->upload(true)) {
                        Yii::$app->getSession()->setFlash('success', 'File uploaded url - '.$article->getSavePath().'/'.$fileUploadModel->type.'/'.$fileUploadModel->filename);
                    }
                }
            }
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
  
        return $this->render('article-collection', ['articleModel' => $article, 'collection' => $attributesData, 'fileUploadModel' => $fileUploadModel]);
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
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Synonym was add success'), false);
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
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Synonym was not delete!'));
        }

        return $this->redirect('@web/iza/synonyms');
    }
       
    public function actionArticleDelete($id) {
        
        try {
            
            $model = Article::findOne($id);
            
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $eavFactory = new StorageEav();
            $eavEntityModel = $eavFactory->factory('entity');
            $eavTypeModel = $eavFactory->factory('type');

            $entity = $eavEntityModel->find()
                                    ->alias('e')
                                    ->innerJoin(['t' => $eavTypeModel::tableName()], 'e.type_id = t.id')
                                    ->where(['e.model_id' => $id, 't.name' => 'article'])
                                    ->one();
            
            if ($entity->id) {
                
                if ($entity->delete()) {
                    $model->delete();
                    $this->removeOldFolders($model->getSavePath());
                }
            }

            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Article was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Article was not delete!'));
        }
             
        return $this->redirect('@web/iza/articles');
    }
    
    protected function removeOldFolders($path) {
        
        $folders = [];
        $folders['frontend'] = Yii::getAlias('@frontend').'/web'.$path;
        $folders['backend'] = Yii::getAlias('@backend').'/web'.$path;
        
        foreach ($folders as $dir) {

            if (is_dir($dir)) {
                $it = new \RecursiveDirectoryIterator($dir);
                $it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
                foreach($it as $file) {
                    if ('.' === $file->getBasename() || '..' ===  $file->getBasename()) continue;
                    if ($file->isDir()) rmdir($file->getPathname());
                    @unlink($file->getPathname());
                }
                @rmdir($dir);
            }
        }
    }
    
    public function actionAuthorDelete($id) {
        
        try {
            
            $model = Author::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $eavFactory = new StorageEav();
            $eavEntityModel = $eavFactory->factory('entity');
            $eavTypeModel = $eavFactory->factory('type');

            $entity = $eavEntityModel->find()
                                    ->alias('e')
                                    ->innerJoin(['t' => $eavTypeModel::tableName()], 'e.type_id = t.id')
                                    ->where(['e.model_id' => $id, 't.name' => 'author'])
                                    ->one();
            
            if ($entity->id) {
                
                if ($entity->delete()) {
                    $model->delete();
                }
            }
            
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Author was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Author was not delete!'));
        }
             
        return $this->redirect('@web/iza/authors');
    }

}
