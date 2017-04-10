<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
?>

<div class="col-sm-12 sidenav">
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'author_key') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'url') ?>
        <?= $form->field($model, 'avatar')->fileInput()->widget(FileInput::classname(), [
            'options' => ['multiple' => false],
            'pluginOptions' => [
                'allowedFileExtensions' => ['jpg', 'gif', 'png', 'bmp', 'jpeg', 'jepg'],
                'initialPreview' => '',
                'initialPreviewAsData' => true,
                'overwriteInitial' => true,
                'showUpload' => false,
                'initialCaption' => $model->avatar,
                'initialPreviewConfig' => [],
                'overwriteInitial' => true
            ],
        ]);
        ?>
        <?= $form->field($model, 'url_key') ?>
        <?= $form->field($model, 'enabled')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
