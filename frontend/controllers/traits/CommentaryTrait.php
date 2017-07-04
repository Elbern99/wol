<?php
namespace frontend\controllers\traits;

use Yii;
use common\models\Category;
use common\models\Opinion;

trait CommentaryTrait {
    
    protected function getMainCategory($name) 
    {
        return Category::find()->where([
            'url_key' => $name,
        ])->one();
    }
    
    
    protected function getOpinionsList($limit = null)
    {
        $query = Opinion::find()
                        ->select(['id','title', 'url_key', 'image_link', 'created_at', 'short_description'])
                        ->with(['opinionAuthors' => function($query) {
                            return $query->select(['opinion_id','author_name', 'author_url'])->orderBy('author_order')->asArray();
                        }])
                        ->andWhere(['enabled' => 1])
                        ->orderBy('id desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->all();
    }
    
}