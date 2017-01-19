<?php

namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;

class SubjectAreasWidget extends Widget {

    public $param;
    private $prefix = 'filter_subject_type';

    public function init() {
        parent::init();
    }

    protected function getFilter() {

        $content = '';
        $categories = $this->param['data'];

        if (is_array($categories) && count($categories)) {

            $selected = $this->param['selected'];
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


                if (isset($selected[$node['id']])) {
                    $labelContent .= Html::input('checkbox', $this->prefix . '[]', $node['id'], $this->isChecked($node['id']));
                    $spanContent = $nodeTitle;
                    $spanContent .= Html::tag('strong', '(' . $selected[$node['id']] . ')', ['class' => "count"]);
                    $labelContent .= Html::tag('span', $spanContent, ['class' => "label-text"]);
                } else {
                    $labelContent .= Html::input('checkbox', $this->prefix . '[]', $node['id'], ['disabled' => 'disabled']);
                    $labelContent .= Html::tag('span', $nodeTitle, ['class' => "label-text"]);
                }

                $content .= Html::beginTag('li', ['class' => $css]) .
                        Html::tag('label', $labelContent, ['class' => 'def-checkbox light']);

                ++$counter;
            }

            $content .= str_repeat("</li></ul>", $nodeDepth - 1) . "</li>";
            $content .= "</ul>";
        }

        return $content;
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
        return $this->getFilter();
    }

}
