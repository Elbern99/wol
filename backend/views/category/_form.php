<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015
 * @package   yii2-tree-manager
 * @version   1.0.5
 */

use kartik\form\ActiveForm;
use kartik\tree\Module;
use kartik\tree\TreeView;
use kartik\tree\models\Tree;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View       $this
 * @var Tree       $node
 * @var ActiveForm $form
 * @var string     $keyAttribute
 * @var string     $nameAttribute
 * @var string     $iconAttribute
 * @var string     $iconTypeAttribute
 * @var string     $iconsList
 * @var string     $action
 * @var array      $breadcrumbs
 * @var array      $nodeAddlViews
 * @var mixed      $currUrl
 * @var bool       $showIDAttribute
 * @var bool       $showFormButtons
 */
?>

<?php
/**
 * SECTION 1: Initialize node view params & setup helper methods.
 */
?>
<?php
extract($params);
$isAdmin = ($isAdmin == true || $isAdmin === "true"); // admin mode flag
$inputOpts = [];                                      // readonly/disabled input options for node
$flagOptions = ['class' => 'kv-parent-flag'];         // node options for parent/child

// parse parent key
if (empty($parentKey)) {
    $parent = $node->parents(1)->one();
    $parentKey = empty($parent) ? '' : Html::getAttributeValue($parent, $keyAttribute);
}

// get module and setup form
$module = TreeView::module(); // the treemanager module
$formOptions['id'] = 'kv-' . uniqid();
$form = ActiveForm::begin([   // the active form instance
    'action' => $action,
    'options' => $formOptions
]);
// the primary key input field
if ($showIDAttribute) {
    $options = ['readonly' => true];
    if ($node->isNewRecord) {
        $options['value'] = Yii::t('kvtree', '(new)');
    }
    $keyField = $form->field($node, $keyAttribute)->textInput($options);
} else {
    $keyField = Html::activeHiddenInput($node, $keyAttribute);
}

// initialize for create or update
$depth = ArrayHelper::getValue($breadcrumbs, 'depth');
$glue = ArrayHelper::getValue($breadcrumbs, 'glue');
$activeCss = ArrayHelper::getValue($breadcrumbs, 'activeCss');
$untitled = ArrayHelper::getValue($breadcrumbs, 'untitled');
$name = $node->getBreadcrumbs($depth, $glue, $activeCss, $untitled);
if ($node->isNewRecord && !empty($parentKey) && $parentKey !== 'root') {
    /**
     * @var Tree $modelClass
     * @var Tree $parent
     */
    $depth = empty($breadcrumbsDepth) ? null : intval($breadcrumbsDepth) - 1;
    if ($depth === null || $depth > 0) {
        $parent = $modelClass::findOne($parentKey);
        $name = $parent->getBreadcrumbs($depth, $glue, null) . $glue . $name;
    }
}
if ($node->isReadonly()) {
    $inputOpts['readonly'] = true;
}
if ($node->isDisabled()) {
    $inputOpts['disabled'] = true;
}
if ($node->isLeaf()) {
    $flagOptions['disabled'] = true;
}

// show alert helper
$showAlert = function ($type, $body = '', $hide = true) {
    $class = "alert alert-{$type}";
    if ($hide) {
        $class .= ' hide';
    }
    return Html::tag('div', '<div>' . $body . '</div>', ['class' => $class]);
};

// render additional view content helper
$renderContent = function ($part) use ($nodeAddlViews, $params, $form) {
    if (empty($nodeAddlViews[$part])) {
        return '';
    }
    $p = $params;
    $p['form'] = $form;
    return $this->render($nodeAddlViews[$part], $p);
};
?>

<?php
/**
 * SECTION 2: Initialize hidden attributes. In case you are extending this
 * and creating your own view, it is mandatory to set all these hidden
 * inputs as defined below.
 */
?>
<?= Html::hiddenInput('treeNodeModify', $node->isNewRecord) ?>
<?= Html::hiddenInput('parentKey', $parentKey) ?>
<?= Html::hiddenInput('currUrl', $currUrl) ?>
<?= Html::hiddenInput('modelClass', $modelClass) ?>

<?php
/**
 * SECTION 3: Setup form action buttons.
 */
?>
<div class="kv-detail-heading">
    <?php if (empty($inputOpts['disabled']) || ($isAdmin && $showFormButtons)): ?>
        <div class="pull-right">
            <button type="reset" class="btn btn-default" title="<?= Yii::t('kvtree', 'Reset') ?>">
                <i class="glyphicon glyphicon-repeat"></i>
            </button>
            <button type="submit" class="btn btn-primary" title="<?= Yii::t('kvtree', 'Save') ?>">
                <i class="glyphicon glyphicon-floppy-disk"></i>
            </button>
        </div>
    <?php endif; ?>
    <div class="kv-detail-crumbs"><?= $name ?></div>
    <div class="clearfix"></div>
</div>

<?php
/**
 * SECTION 4: Setup alert containers. Mandatory to set this up.
 */
?>
<div class="kv-treeview-alerts">
    <?php
    $session = Yii::$app->session;
    if ($session->hasFlash('success')) {
        echo $showAlert('success', $session->getFlash('success'), false);
    } else {
        echo $showAlert('success');
    }
    if ($session->hasFlash('error')) {
        echo $showAlert('danger', $session->getFlash('error'), false);
    } else {
        echo $showAlert('danger');
    }
    echo $showAlert('warning');
    echo $showAlert('info');
    ?>
</div>

<?php
/**
 * SECTION 5: Additional views part 1 - before all form attributes.
 */
?>
<?php
echo $renderContent(Module::VIEW_PART_1);
?>

<?php
/**
 * SECTION 6: Basic node attributes for editing.
 */
?>

<?php /* id attribute */ ?>
<?= $keyField ?>
<?php $disabled = ($node->system) ? ['disabled'=>'disabled'] : []; ?>
<div class="row">
    <div class="col-sm-12">
        <?= $form->field($node, $nameAttribute)->textInput() ?>
        <?= $form->field($node, $urlKeyAttribute)->textInput($disabled) ?>

        <?php if (is_null($node->id)): ?>
            <?php $node->$typeAttribute = $node->getType()->getTypeByLabel('Article') ?>
        <?php endif; ?>
      
        <?= Html::activeHiddenInput($node, $typeAttribute) ?>
        
        <?= $form->field($node, $metaTitleAttribute)->textarea() ?>
    </div>
</div>

<?php
/**
 * SECTION 7: Additional views part 2 - before admin zone.
 */
?>
<?= $renderContent(Module::VIEW_PART_2) ?>

<?php
/**
 * SECTION 8: Administrator attributes zone.
 */
?>
<?php if ($isAdmin): ?>
    <h4><?= Yii::t('kvtree', 'Settings') ?></h4>

    <?php
    /**
     * SECTION 9: Additional views part 3 - within admin zone
     * BEFORE mandatory attributes.
     */
    ?>
    <?= $renderContent(Module::VIEW_PART_3) ?>

    <?php
    /**
     * SECTION 10: Default mandatory admin controlled attributes.
     */
    ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($node, 'active')->checkbox() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($node, 'filterable')->checkbox() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($node, 'visible_in_menu')->checkbox() ?>
        </div>
    </div>

    <?php
    /**
     * SECTION 11: Additional views part 4 - within admin zone
     * AFTER mandatory attributes.
     */
    ?>
    <?= $renderContent(Module::VIEW_PART_4) ?>
<?php endif; ?>
<?php ActiveForm::end() ?>

<?php
/**
 * SECTION 12: Additional views part 5 accessible by all users
 * after admin zone.
 */
?>
<?= $renderContent(Module::VIEW_PART_5) ?>
