<?php

namespace backend\controllers\traits;

use backend\modules\cms\AdminSectionFactory;
use Yii;
use common\models\Modules;

/*
 * Extension for cms page controller
 */
trait CmsBaseTrait {
    
    protected $filePagePath = "@backend/views/cms/static-pages/components/page_edit.php";
    protected $items = [];
    public $widgetModuleKey = 'widget';
    
    /*
     * Additional page blocks
     * @property $type string
     * @property $page ActiveRecord
     * @return @object
     */
    protected function getCustomSection($type, $page) {
        
       $obj = new AdminSectionFactory();
       return $obj->create($type, $page);
    }

    /*
     * Generate left menu tab
     * @property $page ActiveRecord
     * @property $page_info ActiveRecord
     * @return array
     */
    protected function getTabItems($page, $page_info) {

        $sectionOpen = Yii::$app->session->getFlash('section_open');

        $this->items[] = [
            'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Page'),
            'content' => $this->renderFile($this->filePagePath, ['page' => $page, 'page_info' => $page_info]),
        ];

        if (isset($page->id)) {
            
            $sectionModule = Modules::find()->where(['id' => $page->modules_id])->select('key')->asArray()->one();
            
            if (isset($sectionModule['key'])) {
                $this->items[] = $this->getCustomSection($sectionModule['key'], $page)->renderSection($sectionOpen);
            }
            
            $this->items[] = $this->getCustomSection($this->widgetModuleKey, $page)->renderSection($sectionOpen);
            
        }

        return $this->items;
    }
}
    
