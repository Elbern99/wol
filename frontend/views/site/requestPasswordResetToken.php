<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container site-request-password-reset without-breadcrumbs">
    <div class="content-inner">
        <div class="content-inner-text">
            <div class="article-heade">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <p>Please fill out your email. A link to reset password will be sent there.</p>
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
            <div class="grid">
                <div class="grid-line two">
                    <div class="grid-item">
                        <div class="form-item">
                            <?= $form->field($model, 'email', ['options'=>['class' => 'form-item']])->textInput(['autofocus' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Send', ['class' => 'btn-blue-large']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
