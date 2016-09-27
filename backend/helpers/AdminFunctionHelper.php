<?php
namespace backend\helpers;

class AdminFunctionHelper {
    
    static $EndString = '...';
    static $DateFormat = 'php:d-m-Y H:i:s';
    
    public static function short(string $text, $maxSize = 30) {

        if (strlen($text) > $maxSize) {
            return substr(strip_tags($text), 0, $maxSize).self::$EndString;
        }
        
        return $text;
    }
    
    public static function dateFormat($date) {
        return \Yii::$app->formatter->asDatetime($date, self::$DateFormat);
    }
}

