<?php
namespace common\modules\eav\helper;

class EavAttributeHelper {
    
    protected static $attributes = [];
    
    public static function initEavAttributes(array $attributes) {
        self::$attributes = $attributes;
    }
    
    public static function getAttribute($name) {
        
        if (isset(self::$attributes[$name])) {
            return self::$attributes[$name];
        }
        
        return self::getEmptyAttribute();
    }
    
    protected static function getEmptyAttribute() {

        return (new class { public function getData($key = null, $lang_key = null) { return null; }});
    }
}