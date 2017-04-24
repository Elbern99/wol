<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use Yii;

class SphinxController extends Controller {
    
    use \common\helpers\SphinxReIndexTrait;
    
    /* php yii sphinx */
    public function actionIndex($index = null) {

        $this->runIndex($index);
    }
}