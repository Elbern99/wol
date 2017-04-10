<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use backend\helpers\EavHtmlHelper;

$helper = new EavHtmlHelper($collection);
?>
<div class="col-sm-12 sidenav">
    <?php $form = ActiveForm::begin(); ?>
    <?= $helper->render() ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <style>

        .textarea-line,
        .input-line {
            margin: 0 0.2% 10px;
        }

        .btn-group {
            margin-bottom: 7px;
        }

        label,
        h3,
        h4 {
            display: block;
            clear: both;
            text-align: left;
        }

    </style>

</div>
