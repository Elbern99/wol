<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\BottomMenu */
/* @var $form ActiveForm */
?>

<div class="col-lg-12">
    <?php $form = ActiveForm::begin([
              'enableClientValidation' => true,
              'enableAjaxValidation' => false,
              'method' => 'post'
          ]); 
    ?>

    <?= $form->field($page_info, 'title') ?>
    <?= $form->field($page_info, 'meta_title') ?>
    <?= $form->field($page_info, 'meta_keywords')->textarea() ?>
    <?= $form->field($page_info, 'meta_description')->textarea() ?>
    <?= $form->field($page, 'enabled')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>