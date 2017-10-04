<?php

namespace frontend\components\widget;


use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;


class CategoryListWidget extends Widget
{


    public $categories;

    public $mainCssClass = null;


    public function init()
    {
        parent::init();

        if ($this->categories && (!is_array($this->categories))) {
            throw new \yii\base\Exception('Array of categories required.');
        }
    }


    public function run()
    {
        if (!$this->categories) {
            return;
        }

        $tree = $this->convertToTree();
        return $this->render('_category_list', ['items' => $tree['items'], 'cssClass' => $this->mainCssClass]);
    }


    /**
     * 2-level tree
     * 
     * @return array
     */
    private function convertToTree()
    {
        $root = [];
        $root['items'] = [];
        $currentLevel = 1;
        $currentContainer = &$root;
        $currentItem = reset($this->categories);

        do {
            if ($currentItem['lvl'] == $currentLevel) {
                $currentContainer['items'][] = $currentItem;
            } elseif ($currentItem['lvl'] > $currentLevel) {
                $currentLevel = $currentItem['lvl'];
                $currentContainer = &$currentContainer['items'][count($currentContainer['items']) - 1];
                $currentContainer['items'] = [];
                $currentContainer['items'][] = $currentItem;
            } else {
                $currentContainer = &$root;
                $currentContainer['items'][] = $currentItem;
                $currentLevel--;
            }
        } while ($currentItem = next($this->categories));

        return $root;
    }
}
