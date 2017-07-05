<?php
namespace frontend\components\articles;

use common\models\Category;
use Yii;
use yii\caching\TagDependency;
use common\contracts\cache\SubjectCache;

trait SubjectTrait {

    protected function getSubjectAreas($parent = null) {
        
        $db = Yii::$app->db;

        return $db->cache(function ($db) use ($parent) {

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
                                ->andWhere(['active' => 1, 'filterable' => 0])
                                ->asArray()
                                ->all();

        }, 0, new TagDependency(['tags' => SubjectCache::cache_key]));
    }
}

