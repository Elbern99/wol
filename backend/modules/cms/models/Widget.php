<?php
namespace backend\modules\cms\models;

use common\modules\cms\SectionInterface;
use common\models\Widget as ModelWidget;
use common\models\CmsPagesWidget;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Widget implements SectionInterface {
    
    private $page;
    protected $fileSectionsPath = "@backend/views/cms/static-pages/components/widget.php";
    
    public function setPage($page) {
        $this->page = $page;
    }
    
    public function renderSection($sectionOpen = false) {

        $items = ArrayHelper::map(ModelWidget::find()->select(['id', 'name'])->all(), 'id', 'name');

        $selected = CmsPagesWidget::find()->where(['page_id' => $this->page->id])->all();
        $model = new ModelWidget();
        
        $model->id = ArrayHelper::getColumn($selected, 'widget_id');

        $result = [
            'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Widget'),
            'content' => Yii::$app->getView()->renderFile($this->fileSectionsPath, [
                'items' => $items,
                'model' => $model,
                'url' => Url::toRoute(['widget', 'page_id' => $this->page->id])
            ]),
            'active' => ($sectionOpen == 'widget') ? true : false
        ];
        
        return $result;
    }
}
