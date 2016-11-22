<?php
namespace backend\modules\cms\models;

use common\modules\cms\SectionInterface;
use common\models\CmsPagesSimple;
use Yii;
use yii\helpers\Url;

/*
 * class for simple type page
 */
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
                'model' => $this->getModel(),
                'url' => Url::toRoute(['simple', 'page_id' => $this->page->id])
            ]),
            'active' => ($sectionOpen == 'simple') ? true : false
        ];
    }
    
    protected function getModel() {
       $model = CmsPagesSimple::find()->where(['page_id' => $this->page->id])->one();
       
       if (is_null($model)) {
           $model = new CmsPagesSimple();
           $model->setAttribute('page_id', $this->page->id);
       }
       
       return $model;
    }
}
