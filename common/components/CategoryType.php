<?php
namespace common\components;

class CategoryType {
    
    static $types = [];
    
    static function addTypes($types) {
        self::$types = $types;
    }
    
    static function getTypes() {
        return self::$types;
    }
    
    static function getTypeByKey($key) {
        return (isset(self::$types[$key])) ? self::$types[$key] : null;
    }
    
    static function setType($key, $value) {
        self::$types[$key] = $value;
    }
} 

