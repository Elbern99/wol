<?php
namespace frontend\components\articles;

use yii\base\Widget;
use yii\helpers\Html;

class SubjectAreas extends Widget
{
    public $category= null;
    public $currentId = null;
    
    public function init()
    {
        parent::init();
    }
    
    protected function getFilter() {
        
        $content = '';

        if (is_array($this->category) && count($this->category)) {
            
            $nodeDepth = $currDepth = $counter = 1;
            $content .= Html::beginTag('ul', ['class' => 'articles-filter-list']);

            foreach ($this->category as $node) {

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

                    $content .= Html::beginTag('ul', ['class' => 'submenu']);
                    $currDepth = $currDepth + ($nodeDepth - $currDepth);
                    
                } elseif ($nodeDepth < $currDepth) {
                    
                    $content .= str_repeat("</li></ul>", $currDepth - $nodeDepth) . "</li>";
                    $currDepth = $currDepth - ($currDepth - $nodeDepth);
                }

                /*if ($isChild) {
                    $css = ' item ';
                }*/
                
                if ($node['id'] == $this->currentId) {
                    $css .= ' open ';
                }

                $css .= ' item';
                $css = trim($css);

                $content .= Html::beginTag('li', ['class' => $css]) .
                        '<div class="icon-arrow"></div>'.
                        Html::a($nodeTitle, $nodeUrlKey);

                ++$counter;
            }

            $content .= str_repeat("</li></ul>", $nodeDepth-1) . "</li>";
            $content .= "</ul>";
        }
        
        return $content;
    }

    public function run()
    {
        return $this->getFilter();
    }
}