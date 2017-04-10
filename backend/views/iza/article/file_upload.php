<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="col-sm-12 sidenav">
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'type')->dropDownList($model->getTypeOptions()) ?>
        <?= $form->field($model, 'filename') ?>

        <?= $form->field($model, 'file')->fileInput()->widget(FileInput::classname(), [
            'options' => [
                'multiple' => false
            ],
        ]);
        ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
