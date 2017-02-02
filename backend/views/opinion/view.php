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
                <?= $form->field($model, 'author_ids')->widget(Select2::classname(), [
                    'data' => $model->authorsList(),
                    'options' => ['placeholder' => 'Select opinion authors', 'multiple' => true],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 10
                    ],
                ])->label($model->getAttributeLabel('author_ids')); ?>
            
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