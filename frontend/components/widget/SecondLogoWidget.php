<?php

namespace frontend\components\widget;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class SecondLogoWidget extends Widget {

    public $param;

    public function init() {
        parent::init();
    }

    private function getContent() {

        $content = '';
        
        if (isset($this->param['image'])) {
            $content = Html::img($this->param['image']);
        }
        
        if (isset($this->param['url']) && $content) {
            $content = Html::a($content, Url::to($this->param['url']), ['target' => 'blank']);
        }
        
        return $content;
    }

    public function run() {
        return $this->getContent();
    }

}
