<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'Reset password';
$this->params['breadcrumbs'][] = 'Reset password';
?>
<div class="container site-reset-password without-breadcrumbs">
    <div class="content-inner">
        <div class="content-inner-text">
            <div class="article-head">
                <h1><?= Html::encode('Reset password') ?></h1>
            </div>
            <p>Please choose your new password:</p>
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
            <div class="grid">
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'password',['options'=>['class' => 'form-item']])->passwordInput(['autofocus' => true]) ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn-blue-large']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
