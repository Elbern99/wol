<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Widget */
/* @var $form ActiveForm */
?>

<div class="col-lg-12">
    <?php $form = ActiveForm::begin([
              'enableClientValidation' => true,
              'enableAjaxValidation' => false,
              'method' => 'post',
              'action' => $url
          ]); 
    ?>

    <?= $form->field($model, 'id')->checkboxList($items)->label('Widgets') ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>