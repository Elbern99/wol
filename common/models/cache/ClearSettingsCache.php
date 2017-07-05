<?php
namespace common\models\cache;

use common\contracts\cache\SettingsCache;
use common\contracts\CacheClearInterface;
use Yii;

class ClearSettingsCache implements CacheClearInterface {
    
    public function clear() {
        $this->clearSettingsCache();
    }
    
    protected function clearSettingsCache() {
        $cache = Yii::$app->cacheFrontend;
        $cache->delete(SettingsCache::cache_key);
    }
}
