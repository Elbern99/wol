<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin([
    'action' => Url::to(['/my-account/change-avatar']), 
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<div class="img">
    <div class="inner">
        <?= $form->field($model, 'avatar')->fileInput(['id'=>'load_image'])->label() ?>
        <img class="user-avatar" src="<?= $model->avatar ? $model->getAvatarUrl($model->avatar): '' ?>" alt="" class=''>
        <div class="icon-photo"></div>
    </div>
    <div class="text">change photo</div>
</div>

<?php ActiveForm::end(); ?>
