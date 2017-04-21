<?php
namespace backend\models;

use backend\components\behavior\DateBehavior;

class Article extends \common\models\Article {
    
    public function behaviors() {
        return [
            DateBehavior::className(),
        ];
    }
    
}

