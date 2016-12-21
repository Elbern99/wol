<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password without-breadcrumbs">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>Please choose your new password:</p>
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <div class="grid">
            <div class="grid-line three">
                <div class="form-item">
                    <?= $form->field($model, 'password',['options'=>['class' => 'form-item']])->passwordInput(['autofocus' => true]) ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn-blue']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
