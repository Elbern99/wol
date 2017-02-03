<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use frontend\models\SignupPopupForm;
    
    $model = new SignupPopupForm();
?>

<div class="login-registration">
    <?php if (Yii::$app->user->isGuest) : ?>
    <ul class="login-registration-list mobile">
        <li class="dropdown dropdown-login">
            <?= $this->renderFile('@app/views/site/login.php'); ?>
        </li>
        <li class="dropdown dropdown-login">
            <a href="#" class="mobile-dropdown-link dropdown-link"><?= Yii::t('app/menu', 'register') ?></a>
            <div class="dropdown-widget">
                <?php $form = ActiveForm::begin(['action'=>['/register'], 'method' => 'post']); ?>
                <div class="form-line">
                    <?= $form->field($model, 'first_name', ['options'=>['class' => 'form-item']])->textInput() ?>
                </div>
                <div class="form-line">
                    <?= $form->field($model, 'last_name', ['options'=>['class' => 'form-item']])->textInput() ?>
                </div>
                <div class="form-line">
                    <?= $form->field($model, 'email', ['options'=>['class' => 'form-item']])->textInput() ?>
                </div>
                <div class="form-line">
                    <?= $form->field($model, 'confirm_email', ['options'=>['class' => 'form-item']])->textInput()->label('re-type your email address') ?>
                </div>
                <div class="form-line">
                    <?= $form->field($model, 'password', ['options'=>['class' => 'form-item']])->passwordInput() ?>
                </div>
                <div class="form-line">
                    <?= $form->field($model, 'confirm_password', ['options'=>['class' => 'form-item']])->passwordInput()->label('re-type your password')?>
                </div>
                <div class="title-checkboxes form-item">
                    <div class="label-holder">
                        <span class="label-text">areas of interest</span>
                    </div>
                    <div class="select-clear-all">
                        <span class="clear-all">Clear all</span>
                        <span class="select-all">Select all</span>
                        <div class="tooltip-dropdown dropdown left">
                            <div class="icon-question tooltip"></div>
                            <div class="tooltip-content drop-content">
                                <div class="icon-close"></div>
                                <p>Selecting your areas of interest will enable us to send you relevant information. We use your areas of interest in the following ways:</p>
                                <ul>
                                    <li>To enable you to receive alerts when articles in your areas of interest are published (if you select the ‘new articles alert’ box below)</li>
                                    <li>To better understand our audience</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="checkboxes-holder">
                    <div class="grid">
                        <div class="checkboxes">
                            <div class="grid-line four">
                                <?= $form->field($model, 'items')->checkboxList($model->getSubjectItems(),[
                                    'item'=> function($index, $label, $name, $checked, $value) {
                                        return '<div class="grid-item"><div class="form-item"><label class="custom-checkbox">'.
                                        Html::checkbox($name, $checked, [
                                            'value' => $value,
                                        ]).'<span class="label-text">'.$label.'</span></label></div></div>';
                                    }
                                ])->label('');
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-line">
                        <?= $form->field($model, 'newsletter')->checkbox()->label('<span class="label-text">I would like to register for the IZA World of Labor newsletter</span>', ['class'=>'def-checkbox']) ?>
                    </div>
                    <div class="form-line">
                        <?= $form->field($model, 'agree', ['options'=>['class' => 'form-item no-required']])->checkbox()->label('<span class="label-text">I agree to the <a href="/terms-and-conditions">terms and conditions</a> and <a href="/privacy-and-cookie-policy">data usage policy</a></span>', ['class'=>'def-checkbox']) ?>
                    </div>
                </div>

                <?= Html::submitButton('Create Account', ['class' => 'btn-blue', 'name' => 'signup-button']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </li>
    </ul>
    <?php else: ?>
    <ul class="login-registration-list">
        <li class="dropdown dropdown-login">
            <a href ="/my-account">Welcome, <?= Yii::$app->user->identity->first_name ?? '' ?></a>
        </li>
        <li class="hide-mobile">
            <?= Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout',
                ['class' => '']
            )
            . Html::endForm() ?>
        </li>
    </ul>
    <?php endif; ?>
</div>