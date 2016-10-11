<?php
namespace backend\components\category;

use kartik\tree\TreeView;
use backend\components\category\CategoryManagerAsset;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/*
 * class use in manager who has category option
 */
class CategoryManager extends TreeView
{
    public $selected = [];
    public $multi = true;


    public $mainTemplate = <<< HTML
        <div class="col-sm-3">
            {wrapper}
        </div>
HTML;


    public $wrapperTemplate = "{header}\n{tree}{footer}";


    public $headerTemplate = <<< HTML
<div class="row">
    <div class="col-sm-6">
        {heading}
    </div>
    <div class="col-sm-6">
        {search}
    </div>
</div>
HTML;
    
    public $footerTemplate = "";
    
    public function init()
    {
        parent::init();
    }

    public function run() {
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
        return strtr($content, [
            '{heading}' => $this->renderHeading(),
            '{search}' => $this->renderSearch()
        ]) . "\n" .
        Html::textInput('kv-node-selected', $this->value, $this->options) . "\n";
    }
    
    public function renderWrapper() {
        $content = strtr($this->wrapperTemplate, [
            '{header}' => $this->renderHeader(),
            '{tree}' => $this->renderTree(),
            '{footer}' => $this->renderFooter(),
        ]);
        return Html::tag('div', $content, $this->treeWrapperOptions);
    }
    
    public function renderHeader() {
        Html::addCssClass($this->headerOptions, 'kv-header-container');
        return Html::tag('div', $this->headerTemplate, $this->headerOptions);
    }

    public function renderSearch() {
        $clearLabel = ArrayHelper::remove($this->searchClearOptions, 'label', '&times;');
        $content = Html::tag('span', $clearLabel, $this->searchClearOptions) . "\n" .
            Html::textInput('kv-tree-search', null, $this->searchOptions);
        return Html::tag('div', $content, $this->searchContainerOptions);
    }

    public function renderHeading() {
        $heading = ArrayHelper::remove($this->headingOptions, 'label', '');
        return Html::tag('div', $heading, $this->headingOptions);
    }
    
    public function renderTree() {
        $struct = $this->_module->treeStructure + $this->_module->dataStructure;
        extract($struct);
        $nodeDepth = $currDepth = $counter = 0;
        $out = Html::beginTag('ul', ['class' => 'kv-tree']) . "\n";
        foreach ($this->_nodes as $node) {

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
                $css = ' kv-parent kv-collapsed';
            }

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
                    Html::beginTag('div', ['class' => 'kv-node-indicators kv-root-node-toggle']) . "\n" .
                    $indicators . "\n" .
                    '</div>' . "\n" .
                    Html::beginTag('div', ['tabindex' => -1, 'class' => 'kv-node-detail']) . "\n" .
                    Html::beginTag('label', ['tabindex' => -1, 'class' => 'kv-node-detail-label']) . "\n" .
                    //$this->renderNodeIcon($nodeIcon, $nodeIconType, $isChild) . "\n" .
                    $box . "\n" .
                    Html::tag('span', $nodeName, ['class' => 'kv-node-label'])  .
                    '</label>' . "\n" .
                    '</div>' . "\n" .
                    '</div>' . "\n";
            ++$counter;
        }
        $out .= str_repeat("</li>\n</ul>", $nodeDepth) . "</li>\n";
        $out .= "</ul>\n";
        
        //Html::addCssClass($this->headerOptions, 'kv-header-container');
        Html::addCssClass($this->headingOptions, 'kv-heading-container');
        Html::addCssClass($this->searchContainerOptions, 'kv-search-container');
        Html::addCssClass($this->searchOptions, 'kv-search-input form-control');
        Html::addCssClass($this->searchClearOptions, 'kv-search-clear');

        if (empty($this->searchClearOptions['title'])) {
            $this->searchClearOptions['title'] = \Yii::t('kvtree', 'Clear search results');
        }       
        
        if (!isset($this->searchOptions['placeholder'])) {
            $this->searchOptions['placeholder'] = \Yii::t('kvtree', 'Search...');
        }
        
        $this->rootOptions['class'] = 'text-primary kv-tree-root kv-collapsed';
        $this->treeOptions['class'] = 'kv-tree-container kv-tree-container-custom';
        $this->rootNodeToggleOptions['class'] = 'kv-root-node-toggle';
        $this->nodeToggleOptions['class'] = 'kv-root-node-toggle';
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

