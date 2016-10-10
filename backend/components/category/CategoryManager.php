<?php
namespace backend\components\category;

use kartik\tree\TreeView;
use backend\components\category\CategoryManagerAsset;
use yii\helpers\Html;

class CategoryManager extends TreeView
{
    public $selected = [];
    public $multi = true;


    public $mainTemplate = <<< HTML
        <div class="col-sm-3">
            {wrapper}
        </div>
HTML;

    public $headerTemplate = '';
    public $footerTemplate = "";
    
    public function init()
    {
        parent::init();
    }
    


    public function run()
    {
        if (!$this->_module->treeStructure['treeAttribute']) {
            $this->allowNewRoots = false;
        }
        $this->_nodes = $this->query->all();
        $this->registerAssets();
        echo $this->renderWidget();
    }

    public function renderWidget() {
        $content = strtr($this->mainTemplate, [
            '{wrapper}' => $this->renderWrapper()
        ]);
        return $content;
    }
    
    public function renderTree() {
        $struct = $this->_module->treeStructure + $this->_module->dataStructure;
        extract($struct);
        $nodeDepth = $currDepth = $counter = 0;
        $out = Html::beginTag('ul', ['class' => 'kv-tree']) . "\n";
        foreach ($this->_nodes as $node) {
            /**
             * @var Tree $node
             */
            if (!$this->isAdmin && !$node->isVisible() || !$this->showInactive && !$node->isActive()) {
                continue;
            }
            /** @noinspection PhpUndefinedVariableInspection */
            $nodeDepth = $node->$depthAttribute;
            /** @noinspection PhpUndefinedVariableInspection */
            $nodeLeft = $node->$leftAttribute;
            /** @noinspection PhpUndefinedVariableInspection */
            $nodeRight = $node->$rightAttribute;
            /** @noinspection PhpUndefinedVariableInspection */
            $nodeKey = $node->$keyAttribute;
            /** @noinspection PhpUndefinedVariableInspection */
            $nodeName = $node->$nameAttribute;
            /** @noinspection PhpUndefinedVariableInspection */
            //$nodeIcon = $node->$iconAttribute;
            /** @noinspection PhpUndefinedVariableInspection */
            //$nodeIconType = $node->$iconTypeAttribute;

            $isChild = ($nodeRight == $nodeLeft + 1);
            $indicators = '';
            $css = '';

            if ($nodeDepth == $currDepth) {
                if ($counter > 0) {
                    $out .= "</li>\n";
                }
            } elseif ($nodeDepth > $currDepth) {
                $out .= Html::beginTag('ul') . "\n";
                $currDepth = $currDepth + ($nodeDepth - $currDepth);
            } elseif ($nodeDepth < $currDepth) {
                $out .= str_repeat("</li>\n</ul>", $currDepth - $nodeDepth) . "</li>\n";
                $currDepth = $currDepth - ($currDepth - $nodeDepth);
            }
            if (trim($indicators) == null) {
                $indicators = '&nbsp;';
            }
            $nodeOptions = [
                'data-key' => $nodeKey,
                'data-lft' => $nodeLeft,
                'data-rgt' => $nodeRight,
                'data-lvl' => $nodeDepth,
                'data-readonly' => 0,
                'data-movable-u' => 0,
                'data-movable-d' => 0,
                'data-movable-l' => 0,
                'data-movable-r' => 0,
                'data-removable' => 0,
                'data-removable-all' => 0,
            ];
            if (!$isChild) {
                $css = ' kv-parent ';
            }
            
            if ($this->showCheckbox && $node->isSelected()) {
                $css .= ' kv-selected ';
            }
            if ($node->isCollapsed()) {
                $css .= ' kv-collapsed ';
            }
            /*if (!$node->isVisible() && $this->isAdmin) {
                $css .= ' kv-invisible';
            }
            if ($node->isDisabled()) {
                $css .= ' kv-disabled ';
            }
            if (!$node->isActive()) {
                $css .= ' kv-inactive ';
            }*/
            $indicators .= $this->renderToggleIconContainer(false) . "\n";
            $indicators .= $this->showCheckbox ? $this->renderCheckboxIconContainer(false) . "\n" : '';
            $css = trim($css);
            if (!empty($css)) {
                Html::addCssClass($nodeOptions, $css);
            }
            
            if ($this->multi) {
                $box = Html::checkbox('category[]', $this->isSelected($node->id), ['value' => $node->id]);
            } else {
                $box = Html::radio('category', $this->isSelected($node->id), ['value' => $node->id]);
            }
            
            $out .= Html::beginTag('li', $nodeOptions) . "\n" .
                    Html::beginTag('div', ['tabindex' => -1, 'class' => 'kv-tree-list']) . "\n" .
                    Html::beginTag('div', ['class' => 'kv-node-indicators']) . "\n" .
                    $indicators . "\n" .
                    '</div>' . "\n" .
                    Html::beginTag('div', ['tabindex' => -1, 'class' => 'kv-node-detail']) . "\n" .
                    //$this->renderNodeIcon($nodeIcon, $nodeIconType, $isChild) . "\n" .
                    Html::tag('span', $nodeName, ['class' => 'kv-node-label'])  .
                    $box . "\n" .
                    '</div>' . "\n" .
                    '</div>' . "\n";
            ++$counter;
        }
        $out .= str_repeat("</li>\n</ul>", $nodeDepth) . "</li>\n";
        $out .= "</ul>\n";
        return Html::tag('div', $this->renderRoot() . $out, $this->treeOptions);
    }
    
    private function isSelected($id) {
        return (array_search($id, $this->selected) !== false) ? true : false;
    }

    /**
     * Registers the client assets for the widget
     */
    public function registerAssets() {
        $view = $this->getView();

        CategoryManagerAsset::register($view);
    }

}

