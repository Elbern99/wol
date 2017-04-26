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
    
    <?= Html::activeCheckboxList($model, 'id', $items, ['item' => function($index, $label, $name, $checked, $value) use ($model) {
        
        $checkboxName = Html::getInputName($model, 'id');
        $orderName = Html::getInputName($model, 'order');
        $orderValue = $model->order[$value] ?? 0;
        
        $checkbox = Html::checkbox($name, $checked, [
            'labelOptions'=>['class' => 'def-checkbox light'],
            'value' => $value,
            'label' => '<span class="label-text">'.$label.'</span>',
        ]);
        
        $order = Html::textInput($orderName.'['.$value.']', $orderValue, [
            'labelOptions'=>['class' => 'form-control'],
        ]);

        return Html::tag('p', 
            $checkbox.'<br><span class="label-text">Order</span>'.$order,
        ['class' => 'item']);
    }]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>