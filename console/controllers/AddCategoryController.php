<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\Category;

/*
 * Command example add category php yii add-category -t=Title -m=Meta Title -u=Url Key
 */

class AddCategoryController extends Controller {

    public $title;
    public $meta_title;
    public $url_key;

    public function options($actionID) {
        return ['title', 'meta_title', 'url_key'];
    }

    public function optionAliases() {
        return ['t' => 'title', 'm' => 'meta_title', 'u' => 'url_key'];
    }

    public function actionIndex() {

        if ($this->title && $this->meta_title && $this->url_key) {

            $category = \Yii::createObject('\kartik\tree\models\Tree');
var_dump($category);exit;

            if ($category->makeRoot(true, [
                        'title' => $this->title,
                        'meta_title' => $this->meta_title,
                        'url_key' => $this->url_key,
                        'system' => 1])) {

                $this->stdout("Root category created success.", Console::BG_GREEN);
                echo "\n";
                return 1;
            }
            
        } else {
            $this->stdout("Attribute title/meta_title/url_key not exists.", Console::BG_RED);
            echo "\n";
        }

        $this->stdout("Root category not created.", Console::BG_RED);
        echo "\n";

        return 0;
    }

}
