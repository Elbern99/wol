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
        <div class="checkboxes-holder">
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
                                            <p>Selecting your areas of interest helps us to better understand our audience. We use your areas of interest in the following ways:</p>
                                            <ul>
                                                <li>To enable you to receive alerts when articles in your areas of interest are published (if you select the ‘new articles alert’ box below)</li>
                                                <li>To better understand our audience</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid-line four">
                            <?= $form->field($newslatterModel, 'areas_interest')->checkboxList($newslatterModel->getSubjectItems(),[
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
                        <?= Html::activeCheckbox($newslatterModel, 'iza_world',
                            ['labelOptions' => ['class'=>'def-checkbox'],
                            'label' => '<span class="label-text">Subscribe to IZA World of Labor newsletter</span>']
                        ) ?>
                    </div>

                    <div class="form-line">
                        <?= Html::activeCheckbox($newslatterModel, 'interest',
                            ['labelOptions' => ['class'=>'def-checkbox'],
                            'label' => '<span class="label-text">Subscribe to Article Alerts</span>']
                        ) ?>
                    </div>
                </div>
                <a href="/my-account/delete" class="account-delete">delete account</a>
            </div>
        </div>
        <?= Html::submitButton('Update', ['class' => 'btn-blue-large', 'name' => 'signup-button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>