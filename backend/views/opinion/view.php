<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use dosamigos\ckeditor\CKEditor;
use kartik\file\FileInput;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\UrlRewrite */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Opinions'), 'url' => Url::toRoute('/opinion')];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$this->registerJsFile(Url::to(['/js/dynamically_fields.js']), ['depends' => [\yii\web\JqueryAsset::className()]]);

$config = [
    'wrapper' => '.input_fields_wrap',
    'add_button' => '.add_field_button',
    'model_field_name' => Html::getInputName($model, 'author_ids'),
    'fields' => [
        ['name' => 'author_name', 'type' => 'text', 'label' => 'Name'],
        ['name' => 'author_url', 'type' => 'text', 'label' => 'Url'],
        ['name' => 'author_order', 'type' => 'text', 'label' => 'Order']
    ],
    'data' => $model->author_ids
];

$this->registerJs("dynamicallyFields.init(".json_encode($config).");", 3);
?>
<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'url_key') ?>
                <?= $form->field($model, 'title') ?>
            
                <?= $form->field($model, 'created_at') ?>
            
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
                <?php if ($model->image_link) : ?>
                <a href="#" id="remove-link">Remove image</a>
                <?= $form->field($model, 'delete_file')->hiddenInput(['value'=> 0, 'id' => 'delete-image'])->label(false); ?>
                <?php endif; ?>
                
                <div class="form-group input_fields_wrap">
                    <div>
                        <h3>Authors</h3>
                        <p><button class="add_field_button">Add More Author</button></p>
                    </div>
                </div>
             
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php 
if ($model->image_link) {
$url = Url::toRoute('delete-image');
$this->registerJs(<<<JS
   $('#remove-link').click(function (e) {
      e.preventDefault();
      $.post("$url", { id: $model->id }, function( data ) {
         alert(data);
         $('.file-caption-name').html('<i class="glyphicon glyphicon-file kv-caption-icon"></i>');
      });
   });
JS
,\yii\web\View::POS_END);
}
?>