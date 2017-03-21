<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\BottomMenu */
/* @var $form ActiveForm */
?>

<?php
$this->registerJsFile(Url::to(['/js/dynamically_fields.js']), ['depends' => [\yii\web\JqueryAsset::className()]]);

$config = [
    'wrapper' => '.input_fields_wrap',
    'add_button' => '.add_field_button',
    'model_field_name' => Html::getInputName($page_info, 'breadcrumbs'),
    'fields' => [
        ['name' => 'url', 'type' => 'text', 'label' => 'Url'],
        ['name' => 'label', 'type' => 'text', 'label' => 'Label']
    ],
    'data' => $page_info->getBreadcrumbsArray()
];

$this->registerJs("dynamicallyFields.init(".json_encode($config).");", 3);
?>
<div class="col-lg-12">
    <?php 
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'method' => 'post'
    ]); 
    ?>
    <?= $form->field($page, 'url')->textInput(['readonly' => ($page->system) ? true : false]) ?>
    <?= $form->field($page_info, 'title') ?>
    <?= $form->field($page_info, 'meta_title') ?>
    <?= $form->field($page_info, 'meta_keywords')->textarea() ?>
    <?= $form->field($page_info, 'meta_description')->textarea() ?>
    <?= $form->field($page, 'enabled')->checkbox() ?>

    <div class="form-group input_fields_wrap">
        <div>
            <h3>Breadcrumbs</h3>
            <p><button class="add_field_button">Add More Fields</button></p>
        </div>
    </div>
    <?= Html::activeHiddenInput($page, 'modules_id'); ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>