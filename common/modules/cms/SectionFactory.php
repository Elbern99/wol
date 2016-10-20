<?php

namespace common\modules\cms;

use \yii\db\ActiveRecord;

abstract class SectionFactory {
    
    abstract protected function createSection($type);
    
    public function create($type, ActiveRecord $page) {
        
        $obj = $this->createSection($type);
        $obj->setPage($page);
        
        return $obj;
    }
}