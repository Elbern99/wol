<?php

namespace common\helpers;

use Yii;

/*
 * extension for model with upload file
 */
trait SphinxReIndexTrait {

    protected function runIndex($index = null) {

        if ($index) {
            $script = \Yii::$app->getBasePath().'/../reindexsearch.sh '.\Yii::$app->getBasePath().' '.$index;
        } else {
            $script = \Yii::$app->getBasePath().'/../reindexsearch.sh '.\Yii::$app->getBasePath();
        }

        return shell_exec($script);
    }

}

