<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use common\models\Article;
use common\modules\eav\Collection;
use yii\helpers\ArrayHelper;
use common\models\FavoritArticle;
use common\modules\eav\CategoryCollection;
use common\models\Author;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use frontend\models\Cite;
use common\modules\eav\helper\EavValueHelper;
/**
 * Site controller
 */
class ArticleController extends Controller {
    
    use \frontend\components\articles\SubjectTrait;
    use \frontend\components\articles\ArticleTrait;
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'download-cite' => ['get'],
                ],
            ],
        ];
    }
    
    public function actionIndex() {
        
        $limit = Yii::$app->params['article_limit'];

        if (Yii::$app->request->getIsPjax()) {

            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev;
            }

        }
        
        $order = SORT_DESC;

        if (Yii::$app->request->get('sort')) {
            $order = SORT_ASC;
        }
        
        $category = $this->getMainArticleCategory();
        $subjectAreas = $this->getSubjectAreas($category);
        
        $categoryFormat = ArrayHelper::map($subjectAreas, 'id', function($data) {
            return ['title'=>$data['title'], 'url_key'=>$data['url_key']];
        });

        $articles = $this->getArticlesList($limit, $order);
        
        $articlesIds = ArrayHelper::getColumn($articles, 'id');
        
        $categoryCollection = Yii::createObject(CategoryCollection::class);
        $categoryCollection->setAttributeFilter(['teaser', 'abstract']);
        $categoryCollection->initCollection(Article::tableName(), $articlesIds);
        $values = $categoryCollection->getValues();
        $articlesCollection = [];

        foreach ($articles as $article) {
            
            $articleCategory = [];
            $authors = [];
            
            foreach ($article->articleCategories as $c) {

                if (isset($categoryFormat[$c->category_id])) {

                    $articleCategory[] = '<a href="'.$categoryFormat[$c->category_id]['url_key'].'" >'.$categoryFormat[$c->category_id]['title'].'</a>';
                }
            }

            if (count($article->articleAuthors)) {
                
                foreach ($article->articleAuthors as $author) {
                    $authors[] = Html::a($author->author['name'], Author::getAuthorUrl($author->author['url_key']));
                }
            } else {
                $authors[] = $article->availability;
            }
            
            $eavValue = $values[$article->id] ?? [];
            
            $articlesCollection[$article->id] = [
                'title' => $article->title,
                'url' => '/articles/'.$article->seo,
                'authors' => $authors,
                'teaser' => EavValueHelper::getValue($eavValue, 'teaser', function($data) {
                    return $data;
                }),
                'abstract' => EavValueHelper::getValue($eavValue, 'abstract', function($data) {
                    return $data;
                }), 
                'created_at' => $article->created_at,
                'category' => $articleCategory,
            ];
            
        }
        
        return $this->render('index', [
            'category' => $category, 
            'subjectAreas' => $subjectAreas, 
            'collection' => $articlesCollection, 
            'sort' => $order,
            'limit' => $limit,
            'articleCount' => Article::find()->where(['enabled' => 1])->count('id')
        ]);
    }

    public function actionOnePager($slug) {

        return $this->renderArticlePage('one-pager', $slug, true);
    }

    public function actionFull($slug) {

        return $this->renderArticlePage('full', $slug);
    }
    
    public function actionLang($slug, $code) {

        return $this->renderArticlePage('one-pager', $slug, true, $code);
    }

    public function actionMap($slug) {
        
        $model = $this->getArticleCategories($slug);

        if (!is_object($model)) {
            throw new NotFoundHttpException('Page Not Found.');
        }
        
        $records = $model->getRelatedRecords();
        $articleCollection = Yii::createObject(Collection::class);
        $articleCollection->setAttributeFilter(['teaser','title', 'keywords', 'related', 'key_references', 'add_references']);
        $articleCollection->initCollection(Article::tableName(), $model);

        $categories = [];

        if (count($records['articleCategories'])) {

            $categoryIds = ArrayHelper::getColumn($records['articleCategories'], 'category_id');
            $categories = $this->getFullCategoryArticlesArray($categoryIds);
        }
        
        return $this->render('map', [
            'article' => $model,
            'collection' => $articleCollection,
            'categories' => $categories
        ]);
    }
    
    public function actionReferences($slug) {
        
        $model = Article::find()
                    ->where(['seo' => $slug, 'enabled' => 1])
                    ->one();

        if (!is_object($model)) {
            throw new NotFoundHttpException('Page Not Found.');
        }
        
        $articleCollection = Yii::createObject(Collection::class);
        $articleCollection->setAttributeFilter([
                            'title', 'keywords', 'related', 
                            'key_references', 'add_references', 
                            'further_reading'
        ]);
        
        $articleCollection->initCollection(Article::tableName(), $model);
        
        return $this->render('references', [
            'article' => $model,
            'collection' => $articleCollection,
        ]);
    }
    
    public function actionLike($id) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest || !is_object(Yii::$app->user->identity)) {
            return ['message' => 'You have not logged'];
        }
        
        try {
            
            $userId = Yii::$app->user->identity->id;
            $model = new FavoritArticle();
            $model->user_id = $userId;
            $model->article_id = $id;
            
            if ($model->save()) {
                return ['message' => 'Article added to favorites'];
            }
            
        } catch (Exception $ex) {
        } catch (\yii\db\Exception $e) {
            return ['message' => 'You already added this article'];
        }

        return ['message' => 'Bad Request'];
    }
    
    public function actionDownloadCite() {
        
        $cite = new Cite();

        $cite->load(Yii::$app->request->get(), '');

        if($cite->validate()) {
            return Yii::$app->getResponse()->sendContentAsFile($cite->getContent(),'cite.ris');
        }
        
        return $this->goBack();
    }

}
        