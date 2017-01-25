<?php
namespace common\modules\eav\helper;

class EavValueHelper {
    

    public static function getValue(array $values, string $attrCode, \Closure $functConvert, $typeReturn = null) {

        if (!isset($values[$attrCode])) {
            return self::getReturnType($typeReturn);
        }
        
        $attr = unserialize($values[$attrCode]);
        
        return self::convertReturnType($typeReturn, $attr, $functConvert);
    }
    
    public static function getAttributeValue($attribute, \Closure $functConvert, $typeReturn = null) {
        
        return self::convertReturnType($typeReturn, $attribute, $functConvert);
    }
    
    protected static function convertReturnType($typeReturn, $attr, $convert) {
        
        if (is_null($typeReturn)) {
            return $convert($attr);
        }
        
        if (is_array($attr)) {
            
            $data = [];
            
            foreach ($attr as $val) {
                $data[] = $convert($val);
            }
            
            if ($typeReturn == 'array') {
                return $data;
            }
            
            return implode(', ', $data);
            
        } elseif (is_object($attr)) {
            
            if ($typeReturn == 'array') {
                return [
                    $convert($attr)
                ];
            }
            
            return $convert($attr);
        }
        
        return self::getReturnType($typeReturn);
    }
    
    protected static function getReturnType($type) {
        
        switch ($type) {
            
            case 'array':
                return [];
            case 'string';
                return '';
            default :
                return new \stdClass();
        }
    }
}

