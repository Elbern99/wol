<?php
namespace common\modules\eav;

use Yii;

class StorageEav {
    
    private $storage = [];
    private $components;
    
    public function __construct() {
        $this->components = Yii::$app->modules['eav_module']->components;
    }
    
    
    public function getSingletone($name) {
        
        if (!isset($this->storage[$name])) {
            $this->storage[$name] = $this->create($name);
        }
        
        return $this->storage[$name];
    }
    
    public function factory($name) {
        return $this->create($name);
    }
    
    private function create($name) {

        if (isset($this->components[$name])) {
            return Yii::createObject($this->components[$name]);
        }
        
        throw new \Exception('Class '.$name.' did not find');
    }
}

