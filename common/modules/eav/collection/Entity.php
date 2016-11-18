<?php
namespace common\modules\eav\collection;

class Entity {
    
    private $values;
    private $name;
    
    public function __construct(array $values, string $name) {
        
        $this->values = $values;
        $this->name = $name;
    }
    
    public function getValues() {
        return $this->values;
    }
    
    public function getName() {
        return $this->name;
    }

}
