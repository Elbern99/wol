<?php
namespace backend\components\category;

use yii\helpers\Html;
use Yii;

class CategoryView extends \kartik\tree\TreeView {
    
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

            /** @noinspection PhpUndefinedVariableInspection */
            $nodeType = $node->$typeAttribute;

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
                'data-readonly' => true,
                'data-movable-u' => true,
                'data-movable-d' => true,
                'data-movable-l' => true,
                'data-movable-r' => true,
                'data-removable' => true,
                'data-removable-all' => true,
            ];
            if (!$isChild) {
                $css = ' kv-parent ';
            }

            $css = trim($css);
            if (!empty($css)) {
                Html::addCssClass($nodeOptions, $css);
            }
            $out .= Html::beginTag('li', $nodeOptions) . "\n" .
                    Html::beginTag('div', ['tabindex' => -1, 'class' => 'kv-tree-list']) . "\n" .
                    Html::beginTag('div', ['class' => 'kv-node-indicators']) . "\n" .
                    $indicators . "\n" .
                    '</div>' . "\n".
                    Html::beginTag('div', ['tabindex' => -1, 'class' => 'kv-node-detail']) . "\n" .
                    Html::tag('span', $nodeName, ['class' => 'kv-node-label']) . "\n" .
                    '</div>' . "\n" .
                    '</div>' . "\n";
            ++$counter;
        }
        $out .= str_repeat("</li>\n</ul>", $nodeDepth) . "</li>\n";
        $out .= "</ul>\n";
        return Html::tag('div', $this->renderRoot() . $out, $this->treeOptions);
    }
    
    public function renderDetail() {

        $modelClass = $this->query->modelClass;
        $node = $modelClass::findOne($this->displayValue);
        if (empty($node)) {
            $msg = Html::tag('div', $this->emptyNodeMsg, $this->emptyNodeMsgOptions);
            return Html::tag('div', $msg, $this->detailOptions);
        }

        $params = $this->_module->treeStructure + $this->_module->dataStructure + [
            'node' => $node,
            'action' => $this->nodeActions[Module::NODE_SAVE],
            'formOptions' => $this->nodeFormOptions,
            'modelClass' => $modelClass,
            'currUrl' => Yii::$app->request->url,
            'isAdmin' => $this->isAdmin,
            'softDelete' => $this->softDelete,
            'showFormButtons' => $this->showFormButtons,
            'showIDAttribute' => $this->showIDAttribute,
            'nodeView' => $this->nodeView,
            'nodeAddlViews' => $this->nodeAddlViews,
            'breadcrumbs' => $this->breadcrumbs
        ];
        
        $content = $this->render($this->nodeView, ['params' => $params]);
        return Html::tag('div', $content, $this->detailOptions);
    }

}

