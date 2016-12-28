<?php
namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class ContentTypesWidget extends Widget {
    
    public $dataClass;
    public $dataSelect;
    public $filtered = false;
    
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
                $content .= Html::input('checkbox', $this->prefix.'[]', $key, $this->isChecked($key));
                $spanContent = $item;
                $spanContent .= Html::tag('strong', '('.count($this->dataSelect[$model]).')',['class'=>"count"]);
                $content .= Html::tag('span', $spanContent, ['class'=>"label-text"]);
            } else {
                $content .= Html::input('checkbox', $this->prefix.'[]', $key, ['disabled'=>'disabled']);
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
    
    protected function isChecked($id) {
        
        if (is_null($this->filtered)) {
            return [];
        } elseif(is_array($this->filtered) && (array_search($id, $this->filtered) === false)) {
            return [];
        }
        
        return ['checked'=>'checked'];
    }

    public function run()
    {
        return $this->getContent();
    }
    
}

