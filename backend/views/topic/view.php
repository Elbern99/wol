<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use dosamigos\ckeditor\CKEditor;
use kartik\file\FileInput;
use kartik\select2\Select2;


$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Topics'), 'url' => Url::toRoute('/topic')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'url_key') ?>
                <?= $form->field($model, 'title') ?>
            
                <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                    'options' => ['rows' => 8],
                    'preset' => 'standard',
                    'clientOptions'=>[
                        'enterMode' => 2,
                        'forceEnterMode'=>false,
                        'shiftEnterMode'=>1
                    ]
                ]) ?>
                
                <?= $form->field($model, 'short_description')->widget(CKEditor::className(), [
                    'options' => ['rows' => 8],
                    'preset' => 'standard',
                    'clientOptions'=>[
                        'enterMode' => 2,
                        'forceEnterMode'=>false,
                        'shiftEnterMode'=>1
                    ]
                ]) ?>
            
                <?=
                $form->field($model, 'image_link')->fileInput()->widget(FileInput::classname(), [
                    'options' => ['multiple' => false],
                    'pluginOptions' => [
                        'allowedFileExtensions' => ['jpg', 'gif', 'png', 'bmp', 'jpeg', 'jepg'],
                        'initialPreview' => '',
                        'initialPreviewAsData' => true,
                        'overwriteInitial' => true,
                        'showUpload' => false,
                        'initialCaption' => $model->image_link,
                        'initialPreviewConfig' => [],
                        'overwriteInitial' => true
                    ],
                ]);
                ?>
            
                <?= $form->field($model, 'is_key_topic')->checkbox() ?>
                <?= $form->field($model, 'sticky_at')->checkbox() ?>
            
                <?= $form->field($model, 'article_ids')->widget(Select2::classname(), [
                    'data' => $model->articlesList(),
                    'options' => ['placeholder' => 'Select articles videos...', 'multiple' => true],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 10
                    ],
                ])->label($model->getAttributeLabel('article_ids')); ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>