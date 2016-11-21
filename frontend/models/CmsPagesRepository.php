<?php
namespace frontend\models;

use common\models\CmsPages;
use common\models\Modules;
use common\models\CmsPageInfo;
use Yii;
use Exception;

class CmsPagesRepository {
    
    public static function getPageById($id) {

        $cms = CmsPages::find()
                ->alias('page')
                ->where(['page.id' => $id, 'page.enabled' => 1])
                ->innerJoin(['module' => Modules::tableName()], 'page.modules_id = module.id')
                ->innerJoin(['info' => CmsPageInfo::tableName()], 'page.id = info.page_id')
                ->select(['module.key', 'page.id',
                    'info.title', 'info.meta_title', 
                    'info.meta_keywords',
                    'info.meta_description'
                ])
                ->asArray()
                ->one();
        
        if (!isset($cms['key'])) {
            throw new Exception();
        }

        $page = self::getPageModel($cms['key'])->getContents($cms['id']);
        $widgets = self::getPageModel('widget')->getPageWidgets($cms['id']);
        
        return new Page($cms, $widgets, $page);

    }

    protected static function getPageModel($key) {
        
        $params = Yii::$app->params['cms_page_modules'];
        
        if (isset($params[$key])) {
            return Yii::createObject($params[$key]);
        }

        throw new Exception();
    }
}
