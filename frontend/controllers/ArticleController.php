<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use common\models\Article;
use common\models\Author;
use common\modules\eav\Collection;
use yii\helpers\ArrayHelper;
use common\models\Category;
use common\models\Lang;
use common\modules\eav\CategoryCollection;

/**
 * Site controller
 */
class ArticleController extends Controller {

    public function actionIndex($sort = 0) {
        
        $order = SORT_DESC;
        
        if ($sort) {
            $order = SORT_ASC;
        }
        
        $category = Category::find()
                             ->where(['url_key' => 'articles'])
                             ->select([
                                 'root', 'lvl', 'lft', 'rgt',
                                 'url_key', 'title', 
                                 'description', 'meta_title',
                                 'meta_keywords'
                             ])
                             ->one();
        
        $subjectAreas = $category->children()
                                 ->select([
                                    'id', 'title', 
                                    'url_key','root', 
                                    'lvl', 'lft', 'rgt'
                                 ])
                                 ->asArray()
                                 ->all();
        $categoryFormat = ArrayHelper::map($subjectAreas, 'id', function($data) {
            return ['title'=>$data['title'], 'url_key'=>$data['url_key']];
        });
        
        $articles = Article::find()
                             ->select(['id','title','seo', 'availability', 'created_at'])
                             ->where(['enabled' => 1])
                             ->with(['articleCategories' => function($query) {
                                 return $query->select(['category_id', 'article_id']);
                             }])
                             ->orderBy(['created_at' => $order])
                             ->limit(10)
                             ->all();
        
        $articlesIds = ArrayHelper::getColumn($articles, 'id');
        
        $categoryCollection = Yii::createObject(CategoryCollection::class);
        $categoryCollection->initCollection(Article::tableName(), $articlesIds);
        $values = $categoryCollection->getValues();
        $articlesCollection = [];
 
        foreach ($articles as $article) {
            
            $articleCategory = [];
            
            foreach ($article->articleCategories as $c) {

                if (isset($categoryFormat[$c->category_id])) {

                    $articleCategory[] = '<a href="'.$categoryFormat[$c->category_id]['url_key'].'" >'.$categoryFormat[$c->category_id]['title'].'</a>';
                }
            }
            
            $articlesCollection[$article->id] = [
                'title' => $article->title,
                'url' => '/articles/'.$article->seo,
                'availability' => $article->availability,
                'teaser' => unserialize($values[$article->id]['teaser']),
                'abstract' => unserialize($values[$article->id]['abstract']), 
                'created_at' => $article->created_at,
                'category' => $articleCategory,
            ];
        }
        
        return $this->render('index', ['category' => $category, 'subjectAreas' => $subjectAreas, 'collection' => $articlesCollection]);
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

    private function renderArticlePage($template, $slug, $multiLang = false, $langCode = null) {

        try {
            
            $model = Article::find()
                    ->with([
                        'articleAuthors.author' => function($query) {
                            return $query->select(['id', 'avatar']);
                        }, 
                        'articleCategories' => function($query) {
                            return $query->select(['category_id', 'article_id'])->asArray();
                        }
                    ])
                    ->where(['seo' => $slug, 'enabled' => 1])
                    ->one();

            if (!is_object($model)) {
                throw new NotFoundHttpException('Page Not Found.');
            }
            
            $records = $model->getRelatedRecords();
            $articleCollection = Yii::createObject(Collection::class);
            $articleCollection->initCollection(Article::tableName(), $model, $multiLang);


            $authors = [];
            $categories = [];
            $langs = [];
            $currentLang = null;
            
            if (isset($records['articleAuthors'])) {

                foreach ($records['articleAuthors'] as $author) {

                    $authorCollection = Yii::createObject(Collection::class);
                    $authorCollection->setAttributeFilter(['affiliation', 'name']);
                    $authorCollection->initCollection(Author::tableName(), $author->author);

                    $authors[$author->author->id] = [
                        'collection' => $authorCollection,
                        'avatar' => $author->author->getAvatarBaseUrl()
                    ];
                }
            }

            if (count($records['articleCategories'])) {

                $categoryIds = ArrayHelper::getColumn($records['articleCategories'], 'category_id');
                $categories = Category::find()
                        ->alias('c1')
                        ->leftJoin([
                            'c2' => Category::tableName()], 'c2.lft < c1.lft and c2.rgt > c1.rgt'
                        )
                        ->where(['c1.id' => $categoryIds])
                        ->andWhere(['>=', 'c2.lvl', 1])
                        ->select([
                            'c2.id as p_id',
                            'c1.title', 'c1.url_key',
                            'c2.title as p_title', 'c2.url_key as p_url_key',
                        ])
                        ->asArray()
                        ->all();
            }
            
            if ($articleCollection->isMulti) {
                
                $currentLang = 0;
                
                if (is_null($langCode)) {
                    
                    $langIds = $articleCollection->getLanguages();
                    $langs = Lang::find()->where(['id' => $langIds])->asArray()->all();
                } else {
                    
                    $lang = Lang::find()->where(['code' => $langCode])->select(['id'])->one();

                    if (is_object($lang)) {
                        $currentLang = $lang->id;
                    }
                }
            }

        } catch (\Exception $e) {
            throw new NotFoundHttpException('Page Not Found.');
        } catch (\yii\db\Exception $e) {
            throw new NotFoundHttpException('Page Not Found.');
        }

        return $this->render($template, [
            'article' => $model,
            'collection' => $articleCollection,
            'authors' => $authors,
            'categories' => $categories,
            'currentLang' => $currentLang,
            'langs' => $langs
        ]);
    }

    public function actionMap($slug) {
        
        $model = Article::find()
                    ->with([
                        'articleCategories' => function($query) {
                            return $query->select(['category_id', 'article_id'])->asArray();
                        }
                    ])
                    ->where(['seo' => $slug, 'enabled' => 1])
                    ->one();

        if (!is_object($model)) {
            throw new NotFoundHttpException('Page Not Found.');
        }
        
        $records = $model->getRelatedRecords();
        $articleCollection = Yii::createObject(Collection::class);
        $articleCollection->setAttributeFilter(['title', 'keywords', 'related', 'key_references', 'add_references']);
        $articleCollection->initCollection(Article::tableName(), $model);

        $categories = [];

        if (count($records['articleCategories'])) {

            $categoryIds = ArrayHelper::getColumn($records['articleCategories'], 'category_id');
            $categories = Category::find()
                    ->alias('c1')
                    ->leftJoin([
                        'c2' => Category::tableName()], 'c2.lft < c1.lft and c2.rgt > c1.rgt'
                    )
                    ->where(['c1.id' => $categoryIds])
                    ->andWhere(['>=', 'c2.lvl', 1])
                    ->select([
                        'c2.id as p_id',
                        'c1.title', 'c1.url_key',
                        'c2.title as p_title', 'c2.url_key as p_url_key',
                    ])
                    ->asArray()
                    ->all();
        }
        
        return $this->render('map', [
            'article' => $model,
            'collection' => $articleCollection,
            'categories' => $categories
        ]);
    }

}
        