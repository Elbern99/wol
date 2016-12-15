<?php
namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class ContentTypesWidget extends Widget {
    
    public $dataClass;
    public $dataSelect;
    protected $data;
    protected $prefix = 'filter_content_type';
    
    public function init()
    {
        parent::init();
        $this->data = Yii::createObject($this->dataClass);
    }
    
    private function getContent() {
        
        $content = '';
        
        foreach ($this->data->getHeadingFilter() as $key => $item) {
            
            $model = $this->data->getheadingModelFilter($key);
            
            if (!$model) {
                continue;
            }
            
            $content .= Html::beginTag('li');
            $content .= Html::beginTag('label', ['class'=>"def-checkbox light"]);
            
            if (isset($this->dataSelect[$model])) {
                $content .= Html::input('checkbox', $this->prefix.'[]', $key, ['checked'=>'checked']);
                $spanContent = $item;
                $spanContent .= Html::tag('strong', '('.count($this->dataSelect[$model]).')',['class'=>"count"]);
                $content .= Html::tag('span', $spanContent, ['class'=>"label-text"]);
            } else {
                $content .= Html::input('checkbox', $this->prefix.'[]', $key);
                $content .= Html::tag('span', $item, ['class'=>"label-text"]);
            }
            
            $content .= Html::endTag('label');
            $content .= Html::endTag('li');
        }
        
        if (!$content) {
            return '';
        }
        
        return Html::tag('ul', $content, ['class'=>"checkbox-list"]);
    }

    public function run()
    {
        return $this->getContent();
    }
    
}

