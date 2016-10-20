<?php
namespace backend\modules\cms\models;

use common\modules\cms\SectionInterface;
use Yii;

class Simple implements SectionInterface {
    
    private $page;
    protected $fileSectionsPath = "@backend/views/cms/static-pages/components/simple.php";
    
    public function setPage($page) {
        $this->page = $page;
    }
    
    public function renderSection($sectionOpen = false) {
        
        return [
            'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Simple'),
            'content' => Yii::$app->getView()->renderFile($this->fileSectionsPath, [
                'model' => '',
                'page_id' => $this->page->id
            ]),
            'active' => ($sectionOpen == strtolower(get_class($this))) ? true : false
        ];
    }
}
