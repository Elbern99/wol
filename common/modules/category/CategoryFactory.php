<?php
namespace common\modules\category;

use Yii;

class CategoryFactory {
    
    public static function create($category) {
        
        $type = $category->type;
        
        if (is_null($type)) {
            
            if ($category->lvl == 1) {
                return false;
            }
            
            $type = $category->getRootType();
            
            if (!$type) {
                return false;
            }
        }
        
        $types = new CategoryType();
        $class = $types->getClassById($type);
        
        if ($class) {
            return Yii::createObject($class, [$category]);
        }
        
        return false;
    }
}
