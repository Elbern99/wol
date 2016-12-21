<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="tab active " id="tab-1">
    <div class="account-content">
        <?php $form = ActiveForm::begin([
            'action' => Url::to(['/my-account/edit-user-data']),
        ]); ?>
        <div class="line">
            <div class="label-holder">Name</div>
            <div class="desc">
                <div class="form-item form-item-edit">
                    <?= $model->first_name ?> <a href="" class="edit">edit</a>
                    <div class="hidden">
                        <?= $form->field($model, 'first_name')->textInput(['class'=>"form-control"])->label('') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="line">
            <div class="label-holder">Last Name</div>
            <div class="desc">
                <div class="form-item form-item-edit">
                    <?= $model->last_name ?> <a href="" class="edit">edit</a>
                    <div class="hidden">
                        <?= $form->field($model, 'last_name')->textInput(['class'=>"form-control"])->label('') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="line">
            <div class="label-holder">Email address</div>
            <div class="desc">
                <div class="form-item form-item-edit">
                    <?= $model->email ?> <a href="" class="edit">edit</a>
                    <div class="hidden">
                        <?= $form->field($model, 'email')->textInput(['class'=>"form-control"])->label('') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="line">
            <div class="label-holder">Password</div>
            <div class="desc">
                <div class="form-item form-item-edit">
                    <a href="" class="edit-password">change password</a>
                    <div class="hidden">
                        <?= $form->field($model, 'password_old')->passwordInput(['class'=>"form-control"])->label('Old Password') ?>
                        <?= $form->field($model, 'password')->passwordInput(['class'=>"form-control"])->label('New Password') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="line">
            <div class="label-holder">
                Areas of interest
            </div>
            <div class="desc">
                <div class="grid">
                    <div class="grid-line one">
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
        </div>
        <div class="line line-subscriptions">
            <div class="label-holder">Subscriptions</div>
            <div class="desc">
                <div class="form-line">
                    <label class="def-checkbox">
                        <input type="checkbox" name="name">
                        <span class="label-text">Subscribe to IZA World of Labor newsletter</span>
                    </label>
                </div>
                <div class="form-line">
                    <label class="def-checkbox">
                        <input type="checkbox" name="name">
                        <span class="label-text">Subscribe to Article Alerts</span>
                    </label>
                </div>
            </div>
        </div>
        <?= Html::submitButton('Update', ['class' => 'btn-blue-large', 'name' => 'signup-button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>