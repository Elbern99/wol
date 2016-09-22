<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BottomMenu */
/* @var $form ActiveForm */
?>
<div class="view">
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'title') ?>
            <?= $form->field($model, 'link') ?>
            <?= $form->field($model, 'class') ?>
            <?= $form->field($model, 'order') ?>
            <?= $form->field($model, 'enabled')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>