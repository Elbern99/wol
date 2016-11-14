<?php

namespace backend\modules\cms;

use common\modules\cms\SectionFactory;

/*
 * create object for cms static page
 */
class AdminSectionFactory extends SectionFactory {
    
    
    protected function createSection($type) {
        
        $class = "\backend\modules\cms\models\\".  ucfirst($type);

        if (class_exists($class)) {
            return \Yii::createObject($class);
        }
        
        throw new \Exception('Module Not Found');
    }
}