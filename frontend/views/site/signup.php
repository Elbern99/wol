<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container subscribe-to-newsletter">
    <div class="article-header">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
        <h1>Create your IZA World of Labor account</h1>
    </div>

    <p>Register to personalise your IZA World of Labor site, save your favourite articles and more.</p>
    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <div class="grid">
            <div class="grid-line two">
                <div class="grid-item">
                    <div class="form-item">
                        <?= $form->field($model, 'first_name', ['options'=>['class' => 'label-holder']]) ?>
                    </div>
                </div>
            </div>
            <div class="grid-line two">
                <div class="grid-item">
                    <div class="form-item">
                        <?= $form->field($model, 'last_name', ['options'=>['class' => 'label-holder']]) ?>
                    </div>
                </div>
            </div>
            <div class="grid-line two">
                <div class="grid-item">
                    <div class="form-item">
                        <?= $form->field($model, 'email', ['options'=>['class' => 'label-holder']]) ?>
                    </div>
                </div>
            </div>
            <div class="grid-line two">
                <div class="grid-item">
                    <div class="form-item">
                        <?= $form->field($model, 'confirm_email', ['options'=>['class' => 'label-holder']])->textInput()->label('re-type your email address') ?>
                    </div>
                </div>
            </div>
            <div class="grid-line two">
                <div class="grid-item">
                    <div class="form-item">
                        <?= $form->field($model, 'password', ['options'=>['class' => 'label-holder']])->passwordInput() ?>
                    </div>
                </div>
            </div>
            <div class="grid-line two">
                <div class="grid-item">
                    <div class="form-item">
                        <?= $form->field($model, 'confirm_password', ['options'=>['class' => 'label-holder']])->passwordInput()->label('re-type your password') ?>
                    </div>
                </div>
            </div>

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
                                    <strong>At vero eos et accusamus</strong>
                                    Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis volupt atibus maiores alias consequatur aut per ferendis recusan ciedae doloribus asperiores.
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
            <label class="def-checkbox">
                <input type="checkbox" name="">
                <span class="label-text">I would like to register for the IZA World of Labor newsletter</span>
            </label>
        </div>

        <div class="form-line">
            <?= $form->field($model, 'agree')->checkbox()->label('<span class="label-text">I agree to the <a href="">terms and conditions</a> and <a href="">data usage policy</a></span>', ['class'=>'def-checkbox']) ?>
        </div>

        <?= Html::submitButton('Signup', ['class' => 'btn-blue-large', 'name' => 'signup-button']) ?>
    <?php ActiveForm::end(); ?>
</div>
