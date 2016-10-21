<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\CmsPagesSimple */
/* @var $form ActiveForm */
?>
<div class="col-lg-12">

    <?php $form = ActiveForm::begin([
        'action' => $url,
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
    ]); ?>
        
        <?= Html::activeHiddenInput($model, 'id') ?>
        <?= Html::activeHiddenInput($model, 'page_id') ?>
        <?=
        $form->field($model, 'backgroud')->fileInput()->widget(FileInput::classname(), [
            'options' => ['multiple' => false],
            'pluginOptions' => [
                'allowedFileExtensions' => ['jpg', 'gif', 'png', 'bmp', 'jpeg', 'jepg'],
                'initialPreview' => '',
                'initialPreviewAsData' => true,
                'overwriteInitial' => true,
                'showUpload' => false,
                'initialCaption' => $model->backgroud,
                'initialPreviewConfig' => [],
                'overwriteInitial' => true
            ],
        ]);
        ?>
        <?=
        $form->field($model, 'text')->widget(CKEditor::className(), [
            'options' => ['rows' => 10],
            'preset' => 'standard',
            'clientOptions' => [
                'enterMode' => 2,
                'forceEnterMode' => false,
                'shiftEnterMode' => 1
            ]
        ])
        ?>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app/text', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>