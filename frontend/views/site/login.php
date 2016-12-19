<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\LoginForm;
?>
<?php $model = new LoginForm(); ?>
<a href="#" class="dropdown-link"><?= Yii::t('app/menu', 'Login') ?></a>
<div class="dropdown-widget drop-content">
    <?php $form = ActiveForm::begin(['action' => '/site/login']); ?>
        <div class="form-line">
            <?= $form->field($model, 'email')->textInput() ?>
        </div>
        <div class="form-line">
            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>
        <div class="buttons">
            <?= Html::submitButton('Login', ['class' => 'btn btn-blue', 'name' => 'login-button']) ?>
            <?= Html::a('forgot your password?', ['/reset']) ?>
        </div>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
    <?php ActiveForm::end(); ?>
</div>
