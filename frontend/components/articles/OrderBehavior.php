<?php
namespace frontend\components\articles;

use Yii;

class OrderBehavior { 
    
    const DATE_DESC   = 0;
    const DATE_ASC    = 1;
    const AUTHOR_ASC  = 2;
    const AUTHOR_DESC = 3;
    const TITLE_ASC   = 4;
    const TITLE_DESC  = 5;
    
    public static function getArticleOrder() {
        
        return Yii::$app->request->get('sort');
    }
}
