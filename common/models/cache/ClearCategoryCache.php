<?php
namespace common\models\cache;

use common\contracts\cache\SubjectCache;
use yii\caching\TagDependency;
use common\contracts\cache\MenuCache;
use Yii;
use common\contracts\CacheClearInterface;

class ClearCategoryCache implements CacheClearInterface {
    
    public function clear() {
        $this->clearSubjectCache();
        $this->clearMenuCache();
    }
    
    protected function clearSubjectCache() {
        TagDependency::invalidate(Yii::$app->cacheFrontend, SubjectCache::cache_key);
    }
    
    protected function clearMenuCache() {
        $cache = Yii::$app->cacheFrontend;
        $cache->delete(MenuCache::cache_key);
    }
}
