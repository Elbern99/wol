<?php
namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;

class SubjectAreasWidget extends Widget
{
    public $category = [];
    public $selected = [];
    public $filtered = false;
    
    private $prefix = 'filter_subject_type';
    
    public function init()
    {
        parent::init();
    }
    
    protected function getFilter() {
        
        $content = '';

        if (is_array($this->category) && count($this->category)) {
            
            $nodeDepth = $currDepth = $counter = 1;
            $content .= Html::beginTag('ul', ['class' => 'checkbox-list']);
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
                
                if (isset($this->selected[$node['id']])) {
                    $labelContent .= Html::input('checkbox', $this->prefix.'[]', $node['id'], $this->isChecked($node['id']));
                    $spanContent = $nodeTitle;
                    $spanContent .= Html::tag('strong', '('.$this->selected[$node['id']].')',['class'=>"count"]);
                    $labelContent .= Html::tag('span', $spanContent, ['class'=>"label-text"]);
                } else {
                    $labelContent .= Html::input('checkbox', $this->prefix.'[]', $node['id'], ['disabled'=>'disabled']);
                    $labelContent .= Html::tag('span', $nodeTitle, ['class'=>"label-text"]);
                }
                
                $content .= Html::beginTag('li', ['class' => $css]) .
                        Html::tag('label', $labelContent, ['class' => 'def-checkbox light']);
                
                ++$counter;
            }

            $content .= str_repeat("</li></ul>", $nodeDepth) . "</li>";
            $content .= "</ul>";
        }

        return $content;
    }
    
    protected function isChecked($id) {
        
        if (is_null($this->filtered)) {
            return [];
        } elseif(is_array($this->filtered) && (array_search($id, $this->filtered) === false)) {
            return [];
        }
        
        return ['checked'=>'checked'];
    }

    public function run()
    {
        return $this->getFilter();
    }
}