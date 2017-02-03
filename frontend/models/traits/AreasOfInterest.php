<?php
namespace frontend\models\traits;

use common\models\Category;
use yii\helpers\ArrayHelper;

trait AreasOfInterest {
    
    public function getSubjectItems() {
        
        $categories =  Category::find()
                            ->alias('s')
                            ->select(['c.id', 'c.title'])
                            ->innerJoin(Category::tableName().' AS c', 's.id = c.root')
                            ->where(['s.url_key' => 'articles', 'c.active' => 1, 'c.lvl' => 1, 'c.filterable' => 0])
                            ->asArray()
                            ->all();
        
        return ArrayHelper::map($categories, 'id', 'title');
    }
}

