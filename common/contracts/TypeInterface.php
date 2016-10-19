<?php

namespace common\contracts;

/*
 * Type helper public function
 */
interface TypeInterface {
    
    public function addTypes($types);
    
    public function getTypes();
    
    public function getTypeByLabel($label);
    
    public function setType($key, $value);
    
    public function getTypeByKey($key);
}
