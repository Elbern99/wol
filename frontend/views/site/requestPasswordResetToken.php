<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'Request password reset';
$this->params['breadcrumbs'][] = 'Request password reset';
?>
<div class="container site-request-password-reset without-breadcrumbs">
    <div class="content-inner">
        <div class="content-inner-text">
            <div class="article-head">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <p>Please fill out your email to send a password reset link.</p>
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
            <div class="grid">
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'email', ['options'=>['class' => 'form-item']])->textInput(['autofocus' => true]) ?>
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
