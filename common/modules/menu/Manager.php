<?php

namespace common\modules\menu;

use common\models\Category;
use common\models\MenuLinks;
use common\modules\menu\contracts\MenuManagerInterface;
use yii\helpers\Html;
use yii\helpers\Url;
use Yii;

class Manager implements MenuManagerInterface {

    private $category;
    private $links;
    private $data;
    static $cache_key = 'site_menu';

    public function __construct(Category $category, MenuLinks $links) {
        $this->init($category, $links);
    }
    
    public function getData() {
        
        return $this->data;
    }

    private function setCategoryTree($category) {
        
        $this->category = $category->find()->select([
                    'id', 'title', 'url_key',
                    'root', 'lvl', 'lft', 'rgt'
                ])
                ->where(['active' => 1, 'visible_in_menu' => 1])
                ->andWhere(['<=', 'lvl', 2])
                ->orderBy(['root' => SORT_ASC, 'lft' => SORT_ASC])
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
    
    private function init($category, $links) {
        
        $cache = Yii::$app->cache;
        $data = $cache->get(self::$cache_key);
        
        if (!$data) {
            
            $this->setCategoryTree($category);
            $this->setGroupLinks($links);
            
            $this->data = [
                'top' => $this->getTopMenu(),
                'bottom' => $this->getBottomMenu(),
                'main' => $this->getMainMenu()
            ];

            $cache->set(self::$cache_key, $this->data, 86400);
            return;
        }
        
        $this->data = $data;
    }

    protected function getTopMenu() {

        $content = '';
        
        if (isset($this->links[MenuLinks::TOP_LINK])) {
            $content .= '<li class="item has-drop"><a href="#">For media</a><ul class="submenu"><li class="item"><a href="/find-a-topic-spokesperson">Find a topic spokesperson</a></li><li class="item"><a href="/press-releases">Press Releases</a></li></ul></li>';

            foreach ($this->links[MenuLinks::TOP_LINK] as $item) {

                if ($item['link']) {

                    if (preg_match("/^(http|https):\/\//", $item['link'])) {
                        $options = ['target' => 'blank'];
                    } else {
                        $options = [];
                    }

                    $text = Html::a($item['title'], Url::to($item['link'], true), $options);
                } else {
                    $text = $item['title'];
                }

                $content .= Html::tag('li', $text, ['class' => $item['class']]);
            }

            $content = Html::tag('ul', $content, ['class' => 'header-menu-top-list']);
        }

        return $content;
    }

    protected function getMainMenu() {

        $content = '';

        if (count($this->category)) {

            $nodeDepth = $currDepth = $counter = 0;
            $content = Html::beginTag('div', ['class' => 'header-menu-bottom-list']);
            foreach ($this->category as $node) {

                $nodeDepth = $node['lvl'];
                $nodeLeft = $node['lft'];
                $nodeRight = $node['rgt'];
                $nodeTitle = $node['title'];
                $nodeUrlKey = Url::to($node['url_key'], true);


                $isChild = ($nodeRight == $nodeLeft + 1);
                $css = '';

                if ($nodeDepth == $currDepth) {
                    if ($counter > 0) {
                        $content .= "</div>";
                    }
                } elseif ($nodeDepth > $currDepth) {
                    $content .= Html::beginTag('div', ['class' => 'submenu']);
                    $currDepth = $currDepth + ($nodeDepth - $currDepth);
                } elseif ($nodeDepth < $currDepth) {
                    $content .= str_repeat("</div></div>", $currDepth - $nodeDepth) . "</div>";
                    $currDepth = $currDepth - ($currDepth - $nodeDepth);
                }

                if ($isChild) {
                    $css = ' item';
                }

                if (!$isChild) {
                    $css = ' item';
                }

                $css = trim($css);

                $content .= Html::beginTag('div', ['class' => $css]) .
                        Html::a($nodeTitle, $nodeUrlKey);
                ++$counter;
            }

            $content .= str_repeat("</div></div>", $nodeDepth) . "</div>";
            $content .= "</div>";
        }

        return $content;
    }

    protected function getBottomMenu() {

        $content = '';

        if (isset($this->links[MenuLinks::BOTTOM_LINK])) {

            foreach ($this->links[MenuLinks::BOTTOM_LINK] as $item) {

                if ($item['link']) {

                    if (preg_match("/^(http|https):\/\//", $item['link'])) {
                        $options = ['target' => 'blank'];
                    } else {
                        $options = [];
                    }

                    $text = Html::a($item['title'], Url::to($item['link'], true), $options);
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
