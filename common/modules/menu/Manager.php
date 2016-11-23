<?php

namespace common\modules\menu;

use common\models\Category;
use common\models\MenuLinks;
use common\modules\menu\contracts\MenuManagerInterface;
use yii\helpers\Html;

class Manager implements MenuManagerInterface {

    private $category;
    private $links;

    public function __construct(Category $category, MenuLinks $links) {
        
        $this->setCategoryTree($category);
        $this->setGroupLinks($links);
    }
    
    private function setCategoryTree($category) {
        
        $this->category = $category->find()->select([
                    'id', 'title','url_key',
                    'root', 'lvl'
                ])
                ->where(['active' => 1, 'visible_in_menu' => 1])
                ->andWhere(['<=', 'lvl', 1])
                ->asArray()
                ->all(); 
    }
    
    private function setGroupLinks($links) {
        
        $modelData = $links->find()
                ->select([
                    'title', 'link', 'class', 'type'
                ])
                ->where(['enabled' => 1])
                ->orderBy(['order' => SORT_ASC])
                ->asArray()
                ->all();
        
        foreach ($modelData as $data) {
            $this->links[$data['type']][] = $data;
        }

    }

    public function getTopMenu() {
        
        $content = '';
        
        if (isset($this->links[MenuLinks::TOP_LINK])) {

            foreach ($this->links[MenuLinks::TOP_LINK] as $item) {

                if ($item['link']) {

                    if (preg_match("/^(http|https):\/\//", $item['link'])) {
                        $options = ['target' => 'blank'];
                    } else {
                        $options = [];
                    }

                    $text = Html::a($item['title'], $item['link'], $options);
                } else {
                    $text = $item['title'];
                }

                $content .= Html::tag('li', $text, ['class' => $item['class']]);
            }

            $content = Html::tag('ul', $content, ['class' => 'header-menu-top-list']);
            
        }
        
        return $content;
    }

    public function getMainMenu() {
        
        $content = '';
        
        if (count($this->category)) {
            
            $content .= '<div class="header-menu-bottom-list">';
            $subMenuOpen = false;
            $last = end($this->category);
            
            foreach ($this->category as $category) {
                
                if ($category['lvl'] == 0) {
                    
                    if ($subMenuOpen) {
                        $subMenuOpen = false;
                        $content .= '</div></div>';
                    }
                    
                    $content .= '<div class="item">';
                    $content .= Html::a($category['title'], $category['url_key']);
                    
                    if ($last['id'] == $category['id']) {
                        $content .= '</div>';
                    }
                    continue;
                }

                if (!$subMenuOpen) {
                    $subMenuOpen = true;
                    $content .= '<div class = "submenu">';
                }
                
                $content .= Html::tag('div', Html::a($category['title'], $category['url_key']), ['class'=>'item']);
                
                if ($last['id'] == $category['id']) {
                    $content .= '</div></div>';
                }
            }
            
            $content .= '</div>';
        }
        
        return $content;
    }

    public function getBottomMenu() {
        
        $content = '';

        if (isset($this->links[MenuLinks::BOTTOM_LINK])) {

            foreach ($this->links[MenuLinks::BOTTOM_LINK] as $item) {

                if ($item['link']) {

                    if (preg_match("/^(http|https):\/\//", $item['link'])) {
                        $options = ['target' => 'blank'];
                    } else {
                        $options = [];
                    }

                    $text = Html::a($item['title'], $item['link'], $options);
                } else {
                    $text = $item['title'];
                }

                $content .= Html::tag('li', $text, ['class' => $item['class']]);
            }

            $content = Html::tag('ul', $content, ['class' => 'footer-menu-list']);
        }

        return $content;
    }

}
