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
                    'root', 'lvl', 'lft', 'rgt'
                ])
                ->where(['active' => 1, 'visible_in_menu' => 1])
                ->andWhere(['<=', 'lvl', 2])
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
								        
            $nodeDepth = $currDepth = $counter = 0;
            $content = Html::beginTag('div', ['class' => 'header-menu-bottom-list']);
            foreach ($this->category as $node) {

                $nodeDepth = $node['lvl'];
                $nodeLeft = $node['lft'];
                $nodeRight = $node['rgt'];
                $nodeTitle = $node['title'];
                $nodeUrlKey = $node['url_key'];


                $isChild = ($nodeRight == $nodeLeft + 1);
                $css = '';

                if ($nodeDepth == $currDepth) {
                    if ($counter > 0) {
                        $content .= "</div>";
                    }
                } elseif ($nodeDepth > $currDepth) {
                    $content .= Html::beginTag('div');
                    $currDepth = $currDepth + ($nodeDepth - $currDepth);
                } elseif ($nodeDepth < $currDepth) {
                    $content .= str_repeat("</div>\n</div>", $currDepth - $nodeDepth) . "</div>";
                    $currDepth = $currDepth - ($currDepth - $nodeDepth);
                }

                if ($isChild) {
                    $css = ' item ';
                }
                
                if (!$isChild) {
                    $css = ' item has-drop';
                }

                $css = trim($css);

                $content .= Html::beginTag('div', ['class' => $css]).
                        Html::a($nodeTitle, $nodeUrlKey);

                ++$counter;
            }
            
            $content .= str_repeat("</div>\n</div>", $nodeDepth) . "</div>";
            $content .= "</div>";

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
