<?php

namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class SubjectAreasWidget extends Widget {

    public $param;
    private $prefix = 'filter_subject_type';
    private $enabled = false;

    public function init() {
        parent::init();
        
        if (isset($this->param['types']['filtered'])) {
            $typeObject = Yii::createObject($this->param['types']['data']);
            $this->enabled = in_array($typeObject->getHeadingModelKey('article'), $this->param['types']['filtered']) ? true : false;
        }
    }

    protected function getFilter() {

        $content = '';
        $categories = $this->param['category']['data'];
        
        if (is_array($categories) && count($categories)) {

            $selected = $this->param['category']['selected'];
            $nodeDepth = $currDepth = $counter = 1;
            $content .= Html::beginTag('ul', ['class' => 'checkbox-list']);

            foreach ($categories as $node) {

                $nodeDepth = $node['lvl'];
                $nodeLeft = $node['lft'];
                $nodeRight = $node['rgt'];
                $nodeTitle = $node['title'];
                $nodeUrlKey = $node['url_key'];

                $isChild = ($nodeRight == $nodeLeft + 1);
                $css = '';

                if ($nodeDepth == $currDepth) {

                    if ($counter > 1) {

                        $content .= "</li>";
                    }
                } elseif ($nodeDepth > $currDepth) {

                    $content .= Html::beginTag('ul', ['class' => 'subcheckbox-list']);
                    $currDepth = $currDepth + ($nodeDepth - $currDepth);
                } elseif ($nodeDepth < $currDepth) {

                    $content .= str_repeat("</li></ul>", $currDepth - $nodeDepth) . "</li>";
                    $currDepth = $currDepth - ($currDepth - $nodeDepth);
                }

                $css .= '';
                $css = trim($css);
                $labelContent = '';

                if (is_null($this->param['category']['filtered'])) {
                    $labelContent .= Html::input('checkbox', $this->prefix . '[]', $node['id'], $this->isDisabled());
                    $spanContent = $nodeTitle;
                    $labelContent .= Html::tag('span', $spanContent, ['class' => "label-text"]);
                } elseif (isset($selected[$node['id']])) {
                    $labelContent .= Html::input('checkbox', $this->prefix . '[]', $node['id'], $this->isChecked($node['id']));
                    $spanContent = $nodeTitle;
                    $spanContent .= Html::tag('strong', '(' . $selected[$node['id']] . ')', ['class' => "count"]);
                    $labelContent .= Html::tag('span', $spanContent, ['class' => "label-text"]);
                } else {
                    $labelContent .= Html::input('checkbox', $this->prefix . '[]', $node['id'], ['disabled' => 'disabled']);
                    $labelContent .= Html::tag('span', $nodeTitle, ['class' => "label-text"]);
                }

                $content .= Html::beginTag('li', ['class' => $css]) .
                        Html::tag('label', $labelContent, ['class' => 'def-checkbox light item-filter-box']);

                ++$counter;
            }

            $content .= str_repeat("</li></ul>", $nodeDepth - 1) . "</li>";
            $content .= "</ul>";
        }
        
        if ($this->enabled) {
            $content .= '<a href="" class="clear-all">Clear all</a>';
        }

        return $content;
    }
    
    protected function isDisabled() {
        
        $options = [];
        
        if (!$this->enabled) {
            $options['disabled'] = 'disabled';
        }
        
        return $options;
    }

    protected function isChecked($id) {

        $filtered = $this->param['category']['filtered'];
        $options = $this->isDisabled();
        
        if (!$this->enabled) {
            $options['disabled'] = 'disabled';
        }
        
        if (is_array($filtered) && (array_search($id, $filtered) === false)) {
            return $options;
        }

        $options['checked'] = 'checked';
        
        return $options;
    }

    public function run() {
        return $this->getFilter();
    }

}
