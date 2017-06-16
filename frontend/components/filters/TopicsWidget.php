<?php

namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class TopicsWidget extends Widget {

    public $param;
    protected $prefix = 'filter_topics_type';
    private $enabled = false;

    public function init() {
        parent::init();
        
        if (is_array($this->param['types']['filtered'])) {
            $typeObject = Yii::createObject($this->param['types']['data']);
            $this->enabled = in_array($typeObject->getHeadingModelKey('key_topics'), $this->param['types']['filtered']) ? true : false;
        }
        
        $this->param = $this->param['topics'];
    }

    private function getContent() {

        $content = '';
        $selected = $this->param['selected'];
        
        foreach ($this->param['data'] as $item) {

            $content .= Html::beginTag('li');
            $content .= Html::beginTag('label', ['class' => "def-checkbox light item-filter-box"]);

            if ($selected) {
                $content .= Html::input('checkbox', $this->prefix . '[]', $item['id'], $this->isChecked($item['id']));
                $content .= Html::tag('span', $item['title'], ['class' => "label-text"]);
            } else {
                $content .= Html::input('checkbox', $this->prefix . '[]', $item['id'], $this->isDisabled());
                $content .= Html::tag('span', $item['title'], ['class' => "label-text"]);
            }

            $content .= Html::endTag('li');
        }

        if (!$content) {
            return '';
        }

        $content = Html::tag('ul', $content, ['class' => "checkbox-list"]);
        
        if ($this->enabled) {
            $content .= '<a href="" class="clear-all">Clear all</a>';
        }
        
        return $content;;
    }
    
    protected function isDisabled() {
        
        $options = [];
        
        if (!$this->enabled) {
            $options['disabled'] = 'disabled';
        }
        
        return $options;
    }
    
    protected function isChecked($id) {

        $filtered = $this->param['filtered'];
        $options = $this->isDisabled();
        
        if (is_array($filtered) && (array_search($id, $filtered) === false)) {
            return $options;
        }

        $options['checked'] = 'checked';
        return $options;
    }

    public function run() {
        return $this->getContent();
    }

}
