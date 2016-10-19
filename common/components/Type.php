<?php
namespace common\components;

use common\contracts\TypeInterface;

/*
 * Type class with helper function for modules with field type
 */
class Type implements TypeInterface {
    
    private $types = [];
    
    public function addTypes($types) {
        $this->types = $types;
    }
    
    public function getTypes() {
        return $this->types;
    }
    
    public function getTypeByLabel($label) {
        return array_search($label, $this->types);
    }
    
    public function setType($key, $value) {
        $this->types[$key] = $value;
    }
    
    public function getTypeByKey($key) {
        return (isset($this->types[$key])) ? $this->types[$key] : null;
    }
} 

