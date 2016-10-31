<?php
namespace common\modules\eav;

use common\modules\eav\contracts\AttributeSchemaInterface;

class Attribute implements AttributeSchemaInterface {
    
    private $name, $options, $params;
    
    
    public function __construct($name, $params, $options) {
        
        $this->name = $name;
        $this->params = $params;
        $this->options = $options;
    }
    
    public function getOptions() {
        return $this->options;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getParams() {
        return $this->params;
    }
}

