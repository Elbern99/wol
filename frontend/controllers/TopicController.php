<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Site controller
 */
class TopicController extends Controller {
    
    public function actionIndex()
    {
        return 'Topics index page';
    }
}