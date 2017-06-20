<?php

namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class ContentTypesWidget extends Widget {

    public $param;
    protected $data;
    protected $prefix = 'filter_content_type';

    public function init() {
        parent::init();

        $this->data = Yii::createObject($this->param['data']);
    }

    private function getContent() {

        $content = '';
        $amount = $this->param['amount'];

        foreach ($this->data->getHeadingFilter() as $key => $item) {

            $model = $this->data->getheadingModelFilter($key);

            if (!$model) {
                continue;
            }

            $content .= Html::beginTag('li');
            $content .= Html::beginTag('label', ['class' => "def-checkbox light item-filter-box"]);
            $selected = $this->param['selected'];
            
            if (!is_array($this->param['filtered'])) {
                $content .= Html::input('checkbox', $this->prefix . '[]', $key);
                $content .= Html::tag('span', $item, ['class' => "label-text"]);
            } elseif (!count($selected)) {
                $content .= Html::input('checkbox', $this->prefix . '[]', $key, ['checked' => 'checked']);
                $content .= Html::tag('span', $item, ['class' => "label-text"]);
            } elseif (isset($selected[$model])) {
                $content .= Html::input('checkbox', $this->prefix . '[]', $key, $this->isChecked($key));
                $spanContent = $item;
                $cnt  = isset($amount[$model]) ? $amount[$model] : count($selected[$model]);
                $spanContent .= Html::tag('strong', '(' .$cnt. ')', ['class' => "count"]);
                $content .= Html::tag('span', $spanContent, ['class' => "label-text"]);
            } else {
                $content .= Html::input('checkbox', $this->prefix . '[]', $key, ['disabled' => 'disabled']);
                $content .= Html::tag('span', $item, ['class' => "label-text"]);
            }

            $content .= Html::endTag('label');
            $content .= Html::endTag('li');
        }

        if (!$content) {
            return '';
        }

        return Html::tag('ul', $content, ['class' => "checkbox-list"]);
    }

    protected function isChecked($id) {

        $filtered = $this->param['filtered'];

        if (is_null($filtered)) {
            return ['checked' => 'checked'];
        } elseif (is_array($filtered) && (array_search($id, $filtered) === false)) {
            return [];
        }

        return ['checked' => 'checked'];
    }

    public function run() {
        return $this->getContent();
    }

}
