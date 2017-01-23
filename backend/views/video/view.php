<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\ckeditor\CKEditor;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\UrlRewrite */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Video'), 'url' => Url::toRoute('/video')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'url_key') ?>
                <?= $form->field($model, 'title') ?>
                <?= $form->field($model, 'video') ?>
                <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                    'options' => ['rows' => 8],
                    'preset' => 'standard',
                    'clientOptions'=>[
                        'enterMode' => 2,
                        'forceEnterMode'=>false,
                        'shiftEnterMode'=>1
                    ]
                ]) ?>
                <?= $form->field($model, 'order') ?>
            
                <?= $form->field($model, 'video_ids')->widget(Select2::classname(), [
                    'data' => $model->videosList(),
                    'options' => ['placeholder' => 'Select related videos...', 'multiple' => true],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 10
                    ],
                ])->label($model->getAttributeLabel('video_ids')); ?>
            
                <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
                    'data' => $model->categoriesList(),
                    'options' => ['placeholder' => 'Select category...',],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 10
                    ],
                ])->label($model->getAttributeLabel('category_id')); ?>
            
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>