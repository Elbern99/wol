<?php
namespace backend\helpers;

/*
 * Helper functions for admin panel
 */
class AdminFunctionHelper {
    
    static $EndString = '...';
    static $DateFormat = 'php:d-m-Y H:i:s';
    
    /*
     * cut text by size
     * @param string $text
     * @param int $maxSize
     * 
     * @return string
     */
    public static function short($text, $maxSize = 30) {

        if (strlen($text) > $maxSize) {
            return substr(strip_tags($text), 0, $maxSize).self::$EndString;
        }
        
        return $text;
    }
    
    /*
     * formate timestamp in date
     * @param int $date
     * 
     * @return string
     */
    public static function dateFormat($date) {
        return \Yii::$app->formatter->asDatetime($date, self::$DateFormat);
    }
}

