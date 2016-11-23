<?php
namespace common\modules\settings;

use common\models\Settings;
use Yii;
use yii\helpers\ArrayHelper;

class SettingsRepository implements contracts\SettingsInterface {
    
    private static $repository = [];
    
    public static function initSettings() {
        
        $settings = Settings::find()->select(['name','value'])
                              ->asArray()
                              ->all();
        
        if ($settings) {
            self::$repository = ArrayHelper::map($settings, 'name', function($item) {

               $data = unserialize($item['value']);
               
               if (is_array($data)) {
                   return current($data);    
               }
               
               return $data;
            });
        }

    }
    
    public static function get($key) {
        
        if (isset(self::$repository[$key])) {
            return self::$repository[$key];
        }
    }
    
}