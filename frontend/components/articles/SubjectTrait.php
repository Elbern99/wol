<?php
namespace frontend\components\articles;
use common\models\Category;

trait SubjectTrait {

    protected function getSubjectAreas($parent = null) {
        
        if (is_null($parent)) {
            
            $parent = Category::find()
                             ->where(['url_key' => 'articles'])
                             ->select(['root', 'lvl', 'lft', 'rgt'])
                             ->one();
        }
        
        return $parent->children()
                            ->select([
                               'id', 'title', 
                               'url_key','root', 
                               'lvl', 'lft', 'rgt'
                            ])
                            ->asArray()
                            ->all();
    }
}

