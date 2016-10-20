<?php
namespace backend\modules\cms\models;

use common\modules\cms\SectionInterface;
use common\models\CmsPageSections;
use Yii;
use yii\data\ActiveDataProvider;

class Accordion implements SectionInterface {
    
    private $page;
    protected $fileSectionsPath = "@backend/views/cms/static-pages/components/sections_grid.php";
    
    public function setPage($page) {
        $this->page = $page;
    }
    
    public function renderSection($sectionOpen = false) {

        return [
            'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Accordion'),
            'content' => Yii::$app->getView()->renderFile($this->fileSectionsPath, [
                'dataProvider' => new ActiveDataProvider(['query' => CmsPageSections::find()->where(['page_id' => $this->page->id]), 'pagination' => ['pageSize' => 20]]),
                'page_id' => $this->page->id
            ]),
            'active' => ($sectionOpen == 'accordion') ? true : false
        ];
    }
}
