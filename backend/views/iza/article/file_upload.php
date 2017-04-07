<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use backend\models\UploadArticleFiles;

$model = new UploadArticleFiles();
?>
<div class="col-sm-12 sidenav">
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'type') ?>
        <?= $form->field($model, 'filename')->dropDownList($model->getTypeOptions()) ?>

        <?= $form->field($model, 'file')->fileInput()->widget(FileInput::classname(),[
            'options' => [
                'multiple' => false
            ],
        ]);
        ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
