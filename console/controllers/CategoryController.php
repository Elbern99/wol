<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use console\models\Category;

class CategoryController extends Controller {

    public $title;
    public $meta_title;
    public $url_key;

    /*  Command example add category php yii category param1 param2 ... */
    public function actionIndex($title, $meta_title, $url_key) {
        
        $this->title = $title;
        $this->meta_title = $meta_title;
        $this->url_key = $url_key;

        if ($this->title && $this->meta_title && $this->url_key) {

            $category = new Category([
                            'title' => $this->title,
                            'meta_title' => $this->meta_title,
                            'url_key' => $this->url_key,
                            'system' => 1,
                            'active' => 1,
                            'visible_in_menu' => 1
                        ]);

            if ($category->makeRoot()) {

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
