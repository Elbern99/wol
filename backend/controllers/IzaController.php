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
use yii\helpers\ArrayHelper;
use common\modules\eav\Collection;
use common\models\SynonymsSearch;
use common\modules\eav\StorageEav;
use backend\models\Author;
use backend\models\UploadArticleFiles;
use common\models\ArticleAuthor;
use backend\models\ArticleAuthorForm;
use common\models\VersionsArticle;
use common\modules\eav\helper\EavAttributeHelper;
use yii\base\Event;

/**
 * Article Author Class Controller
 */
class IzaController extends Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'articles', 'authors', 'author-view',
                            'article-view', 'synonyms', 'synonym-view',
                            'synonym-delete', 'article-delete', 'author-delete',
                            'article-author-delete'
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


    public function actionArticles()
    {

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


    protected function changeEnabledAjax($class)
    {

        $post = Yii::$app->request->post();

        if (isset($post['id'])) {
            $model = $class::findOne($post['id']);
            $model->enabled = ($post['enabled'] === 'true') ? 1 : 0;

            if ($model->save()) {

                if ($model instanceof \common\models\Article) {
                    if ($model->enabled) {
                        $createTest = $model->getCreated()->one();

                        if (!$createTest || ($model->doi != $createTest->doi_control)) {

                            if ($createTest) {
                                $createTest->doi_control = $this->article->doi;
                                $createTest->save(false, ['doi_control']);
                            }

                            $articleCollection = Yii::createObject(Collection::class);
                            $articleCollection->initCollection($model::ENTITY_NAME, $model, false);
                            $attributes = $articleCollection->getEntity()->getValues();
                            EavAttributeHelper::initEavAttributes($attributes);

                            if (isset($attributes['full_pdf'])) {
                                //$pdfUrl = Url::to([$attributes['full_pdf']->getData('url'), 'v' => count($model->getArticleVersions()) + 1]);
                                $pdfUrl = Yii::$app->frontendUrlManager->createAbsoluteUrl([$attributes['full_pdf']->getData('url')]);
                            } else {
                                $pdfUrl = null;
                            }

                            //\yii\base\Event::trigger(\common\modules\article\ArticleParser::class, \common\modules\article\ArticleParser::EVENT_ARTICLE_CREATE, $event);
                        }

                        $model->insertOrUpdateCreateRecord();
                    }
                    
                    \common\helpers\ArticleHelper::setupCurrent($model->article_number);
                    Event::trigger(\common\modules\article\ArticleParser::class, \common\modules\article\ArticleParser::EVENT_SPHINX_REINDEX);
                }

                return true;
            }
        }

        return false;
    }


    public function actionAuthors()
    {

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


    public function actionAuthorView($id)
    {

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
                            $model->name = $attributes['first_name'] . ' ' . $attributes['middle_name'] . ' ' . $attributes['last_name'];
                            $model->surname = $attributes['last_name'];
                        }
                    ]
                ];

                $model = new \backend\models\EavValueManager($eavValuesModel, $values, $triggers);

                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Attributes have been updated'));
                } else {
                    $errors = implode('<br>', $model->errors);
                    Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Attributes have not been updated ' . $errors));
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
                        Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Author data have updated'));
                    } else {
                        Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Author data have not updated'));
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
                    'options' => array_map(function($value) {
                            return ['label' => $value['label'], 'type' => $value['type']];
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


    public function actionArticleView($id)
    {

        $eavFactory = new StorageEav();

        $article = Article::findOne($id);
        $articleAuthorForm = new ArticleAuthorForm();
        $fileUploadModel = new UploadArticleFiles([], $article);
        $articleAuthor = new ActiveDataProvider([
            'query' => ArticleAuthor::find()->alias('aa')->where(['article_id' => $id])->with('author'),
            'pagination' => ['pageSize' => 100]
        ]);

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
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Attributes have been updated'));
                } else {
                    $errors = implode('<br>', $model->errors);
                    Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Attributes have not been updated <br>' . $errors));
                }

                return $this->redirect(Url::to(['iza/article-view', 'id' => $id]));
            } elseif ($article->load(Yii::$app->request->post())) {

                if ($article->save()) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Article data have updated'));
                } else {
                    Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Article data have not updated'));
                }

                return $this->redirect(Url::to(['iza/article-view', 'id' => $id]));
            } elseif ($fileUploadModel->load(Yii::$app->request->post())) {

                $fileUploadModel->initUploadProperty();
                $fileUploadModel->filename = ($fileUploadModel->filename) ?
                    $fileUploadModel->filename . '.' . $fileUploadModel->file->extension : $fileUploadModel->file->name;

                if ($fileUploadModel->validate()) {

                    if ($fileUploadModel->upload(true)) {
                        Yii::$app->getSession()->setFlash('success', 'File uploaded url - ' . $article->getSavePath() . '/' . $fileUploadModel->type . '/' . $fileUploadModel->filename);
                    }
                }
            } elseif ($articleAuthorForm->load(Yii::$app->request->post())) {

                if ($articleAuthorForm->addAuthor($id)) {
                    Yii::$app->getSession()->setFlash('success', 'Author have been added');
                }

                return $this->redirect(Url::to(['iza/article-view', 'id' => $id]));
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
                    'options' => array_map(function($value) {
                            return ['label' => $value['label'], 'type' => $value['type']];
                        }, $attribute['eavAttribute']['eavAttributeOptions']),
                    'multi_lang_value' => $value->isMulti(),
                    'value' => $v
                ];

                unset($v);
            }
        }

        $this->getView()->registerJsFile('@web/js/eav_attributes_fields.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        return $this->render('article-collection', [
                'articleModel' => $article,
                'collection' => $attributesData,
                'fileUploadModel' => $fileUploadModel,
                'articleAuthor' => $articleAuthor,
                'articleAuthorForm' => $articleAuthorForm
        ]);
    }


    public function actionSynonyms()
    {

        $model = SynonymsSearch::find()->orderBy('id');
        return $this->render('synonyms', ['dataProvider' => new ActiveDataProvider(['query' => $model, 'pagination' => ['pageSize' => 30]])]);
    }


    public function actionSynonymView($id = null)
    {

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


    public function actionSynonymDelete($id)
    {

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


    public function actionArticleDelete($id)
    {

        try {

            $model = Article::findOne($id);

            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
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

                    $vesions = VersionsArticle::find()->where(['article_id' => $id])->all();

                    if ($vesions) {

                        $versionIds = ArrayHelper::getColumn($vesions, 'id');
                        $eavEntityVersionModel = $eavFactory->factory('entity');
                        $entity = $eavEntityModel->find()
                            ->alias('e')
                            ->innerJoin(['t' => $eavTypeModel::tableName()], 'e.type_id = t.id')
                            ->where(['e.model_id' => $versionIds, 't.name' => VersionsArticle::ENTITY_NAME])
                            ->all();

                        $class = $eavEntityModel::className();
                        $class::deleteAll(['id' => ArrayHelper::getColumn($entity, 'id')]);
                        VersionsArticle::deleteAll(['id' => $versionIds]);
                    }

                    $this->removeOldFolders($model->getSavePath());
                }
            }

            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Article was delete success!'));
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Article was not delete!'));
        }

        return $this->redirect('@web/iza/articles');
    }


    protected function removeOldFolders($path)
    {

        $folders = [];
        $folders['frontend'] = Yii::getAlias('@frontend') . '/web' . $path;
        $folders['backend'] = Yii::getAlias('@backend') . '/web' . $path;

        foreach ($folders as $dir) {

            if (is_dir($dir)) {
                $it = new \RecursiveDirectoryIterator($dir);
                $it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($it as $file) {
                    if ('.' === $file->getBasename() || '..' === $file->getBasename())
                        continue;
                    if ($file->isDir())
                        rmdir($file->getPathname());
                    @unlink($file->getPathname());
                }
                @rmdir($dir);
            }
        }
    }


    public function actionAuthorDelete($id)
    {

        try {

            $model = Author::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
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

                $avatar = $model->getFrontendImagesBasePath() . $model->avatar;

                if ($entity->delete()) {

                    $model->delete();

                    if (file_exists($avatar)) {
                        @unlink($avatar);
                    }
                }
            }

            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Author was delete success!'));
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Author was not delete!'));
        }

        return $this->redirect('@web/iza/authors');
    }


    public function actionArticleAuthorDelete($id)
    {
        $articleId = null;

        try {

            $model = ArticleAuthor::findOne($id);
            $articleId = $model->article_id;

            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }

            $model->delete();

            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Article Author relation was delete success!'));
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Article Author relation was not delete!'));
        }

        return $this->redirect(Url::to(['/iza/article-view', 'id' => $articleId]));
    }
}
