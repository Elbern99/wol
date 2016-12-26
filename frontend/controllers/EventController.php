<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class EventController extends Controller {
    
    public function actionIndex()
    {
        return "Events listing page";
    }
}