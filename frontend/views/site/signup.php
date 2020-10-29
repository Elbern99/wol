<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'Register';
$this->params['breadcrumbs'][] = 'Register';
?>
<div class="container content-inner subscribe-to-newsletter">
    <div class="content-inner-text">
        <div class="article-head">
            <div class="breadcrumbs">
                <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
            </div>
            <h1>Create your IZA World of Labor account</h1>
            <p>Register to personalise your IZA World of Labor site, save your favourite articles and more.</p>
        </div>

        <?php $form = ActiveForm::begin(); ?>
            <div class="grid">
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'first_name', ['options'=>['class' => 'form-item']])->textInput() ?>
                    </div>
                </div>
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'last_name', ['options'=>['class' => 'form-item']])->textInput() ?>
                    </div>
                </div>
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'email', ['options'=>['class' => 'form-item']])->textInput() ?>
                    </div>
                </div>
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'confirm_email', ['options'=>['class' => 'form-item']])->textInput()->label('re-type your email address') ?>
                    </div>
                </div>
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'password', ['options'=>['class' => 'form-item']])->passwordInput() ?>
                    </div>
                </div>
                <div class="grid-line two">
                    <div class="grid-item">
                        <?= $form->field($model, 'confirm_password', ['options'=>['class' => 'form-item']])->passwordInput()->label('re-type your password')?>
                    </div>
                </div>
            </div>

            <div class="checkboxes-holder">
                <div class="grid">
                    <div class="checkboxes">
                        <div class="grid-line one title-checkboxes">
                            <div class="grid-item form-item">
                                <div class="select-clear-all">
                                    <span class="clear-all">Clear all</span>
                                    <span class="select-all">Select all</span>
                                    <div class="tooltip-dropdown dropdown left">
                                        <div class="icon-question tooltip"></div>
                                        <div class="tooltip-content drop-content">
                                            <div class="icon-close"></div>
                                            <p>Selecting your areas of interest will enable us to send you relevant information. We use your areas of interest in the following ways:</p>
                                            <ul>
                                                <li>To enable you to receive email alerts when articles in your areas of interest are published (if you select the ‘new articles alert’ box below). We will only send you alerts for your selected fields.</li>
                                                <li>To inform editorial decisions.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="label-holder">
                                    <span class="label-text">areas of interest</span>
                                </div>
                            </div>
                        </div>

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
                    <?= $form->field($model, 'agree')->checkbox(['id' => 'agree_desktop'])->label('<span class="label-text">I agree to the <a href="/terms-and-conditions">terms and conditions</a> and <a href="/privacy-and-cookie-policy">data usage policy</a></span>', ['class'=>'def-checkbox']) ?>
                </div>
                
                <div class="form-line">
                    <p>
                        You can unsubscribe from our newsletter or new article alerts at any time by clicking 
                        the unsubscribe link in any newsletter or article alerts email. Please read our 
                        <a href="/privacy-and-cookie-policy">privacy policy</a> for information on how we process your data.                        
                    </p>
                    <p>
                        We collect your first and last name so that you have a more personalised look and feel to your IZA World of Labor account. 
                    </p>
                </div>                
            </div>

            <?= 
            // $form->field($model, 'reCaptcha')->widget(
            //     \himiklab\yii2\recaptcha\ReCaptcha2::class
            // ) 
            ?>

            <?= Html::submitButton('Sign up', ['class' => 'btn-blue-large', 'name' => 'signup-button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
