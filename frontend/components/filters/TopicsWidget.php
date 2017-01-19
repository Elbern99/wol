<?php

namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class TopicsWidget extends Widget {

    public $param;
    protected $prefix = 'filter_topics_type';

    public function init() {
        parent::init();
    }

    private function getContent() {

        $content = '';
        $selected = $this->param['selected'];
        
        foreach ($this->param['data'] as $item) {

            $content .= Html::beginTag('li');
            $content .= Html::beginTag('label', ['class' => "def-checkbox light"]);

            if ($selected) {
                $content .= Html::input('checkbox', $this->prefix . '[]', $item['id'], $this->isChecked($item['id']));
                $content .= Html::tag('span', $item['title'], ['class' => "label-text"]);
            } else {
                $content .= Html::input('checkbox', $this->prefix . '[]', $item['id']);
                $content .= Html::tag('span', $item['title'], ['class' => "label-text"]);
            }

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
            return [];
        } elseif (is_array($filtered) && (array_search($id, $filtered) === false)) {
            return [];
        }

        return ['checked' => 'checked'];
    }

    public function run() {
        return $this->getContent();
    }

}
