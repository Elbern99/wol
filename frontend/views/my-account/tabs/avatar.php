<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin([
    'action' => Url::to(['/my-account/change-avatar']), 
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<div class="img-holder img-holder-bg">
    <div class="img" style="background-image: url(<?= $model->avatar ? $model->getAvatarUrl($model->avatar): '' ?>)">
        <div class="inner">
            <?= $form->field($model, 'avatar')->fileInput(['id'=>'load_image'])->label('') ?>
        </div>
        <div class="icon-photo"></div>
        <div class="text">change photo</div>
    </div>
</div>

<?php ActiveForm::end(); ?>
