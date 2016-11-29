<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use common\models\Article;
use common\models\Author;
use common\modules\eav\Collection;
/**
 * Site controller
 */
class ArticleController extends Controller {


    public function actionIndex() {

        die('articles');
        return $this->render('index');
    }
    
    public function actionOnePager($slug) {
        
        try {
            
            $model = Article::find()
                    ->with(['articleAuthors.author' => function($query) {
                        return $query->select(['id', 'avatar']);
                    }])
                    ->where(['seo' => $slug, 'enabled' => 1])
                    ->one();

            if (!is_object($model)) {
                throw new NotFoundHttpException('Page Not Found.');
            }

            $articleCollection = Yii::createObject(Collection::class);
            $articleCollection->initCollection(Article::tableName(), $model);
            
            $articleAuthor = $model->getRelatedRecords();
            $authors = [];
            
            if (isset($articleAuthor['articleAuthors'])) {

                foreach ($articleAuthor['articleAuthors'] as $author) {
                    
                    $authorCollection = Yii::createObject(Collection::class);
                    $authorCollection->initCollection(Author::tableName(), $author->author);

                    $authors[$author->author->id] = [
                        'collection' => $authorCollection,
                        'avatar' => $author->author->getAvatarBaseUrl()
                    ];
                }
            }
            
            
        } catch (\Exception $e) {
            throw new NotFoundHttpException('Page Not Found.');
        } catch (\yii\db\Exception $e) {
            throw new NotFoundHttpException('Page Not Found.');
        }

        return $this->render('one-pager', ['article' => $model, 'collection' => $articleCollection, 'authors' => $authors]);  
    }

    public function actionFull($slug) {
        
        try {

            $model = Article::find()
                    ->where(['seo' => $slug, 'enabled' => 1])
                    ->one();


            if (!is_object($model)) {
                throw new NotFoundHttpException('Page Not Found.');
            }

            $collection = Yii::createObject(Collection::class);
            $collection->initCollection(Article::tableName(), $model, false);
        
        } catch (\Exception $e) {
            throw new NotFoundHttpException('Page Not Found.');
        } catch (\yii\db\Exception $e) {
            throw new NotFoundHttpException('Page Not Found.');
        }

        return $this->render('full', ['article' => $model, 'collection' => $collection]);
    }

    public function actionMap($slug) {
        var_dump($slug);
        exit;
        return $this->render('map');
    }

}
