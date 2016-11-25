<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use common\models\Article;
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
        
        $model = Article::find()->where(['seo' => $slug, 'enabled' => 1])->one();
        
        if (!is_object($model)) {
            throw new NotFoundHttpException('Page Not Found.');
        }

        $collection = Yii::createObject(Collection::class);
        $collection->initCollection(Article::tableName(), $model);

        return $this->render('one-pager', ['article' => $model, 'collection' => $collection]);
    }

    public function actionFull($slug) {
        
        $model = Article::find()
                ->where(['seo' => $slug, 'enabled' => 1])
                ->one();
        
        
        //var_dump($model->getRelatedCategories());exit;
        if (!is_object($model)) {
            throw new NotFoundHttpException('Page Not Found.');
        }

        $collection = Yii::createObject(Collection::class);
        $collection->initCollection(Article::tableName(), $model);
        
        return $this->render('full', ['article' => $model, 'collection' => $collection]);
    }

    public function actionMap($slug) {
        var_dump($slug);
        exit;
        return $this->render('map');
    }

}
