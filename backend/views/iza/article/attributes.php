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
</div>
