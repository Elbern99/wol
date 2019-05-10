<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;
?>
<div class="col-sm-12 sidenav">
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'sort_key') ?>
        <?= $form->field($model, 'seo') ?>
        <?= $form->field($model, 'doi') ?>
        <?= $form->field($model, 'availability') ?>
        <?= $form->field($model, 'publisher') ?>
        <?= $form->field($model, 'created_at')->widget(
            DatePicker::className(), [
                'inline' => true,
                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-M-yyyy'
                ]
        ]);?>
        <?= $form->field($model, 'updated_at')->widget(
            DatePicker::className(), [
                'inline' => true,
                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-M-yyyy'
                ]
        ]);?>
        <?= $form->field($model, 'enabled')->checkbox() ?>
        <?= $form->field($model, 'version_updated_label')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
