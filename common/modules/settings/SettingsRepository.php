<?php
namespace common\modules\settings;

use common\models\Settings;
use Yii;
use yii\helpers\ArrayHelper;
use common\contracts\cache\SettingsCache;

class SettingsRepository implements contracts\SettingsInterface {
    
    private static $repository = [];
    static $cache_key = 'site_settings';
    
    public static function initSettings() {
        
        $cache = Yii::$app->cache;
        $data = $cache->get(SettingsCache::cache_key);
        
        if ($data) {
            self::$repository = $data;
            return;
        }
        
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
            
            if (count(self::$repository)) {
                $cache->set(SettingsCache::cache_key, self::$repository, 86400);
            }
        }

    }
    
    public static function get($key) {
        
        if (isset(self::$repository[$key])) {
            return self::$repository[$key];
        }
    }
    
}