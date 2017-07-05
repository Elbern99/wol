<?php 
use kartik\file\FileInput; 
use yii\helpers\Html;
?>
<div class="form-group">
<?=
FileInput::widget([
    'name' => 'image',
    'options' => ['multiple' => false],
    'pluginOptions' => [
        'allowedFileExtensions' => ['jpg', 'gif', 'png', 'bmp', 'jpeg', 'jepg', 'svg'],
        'initialPreview' => '',
        'initialPreviewAsData' => true,
        'overwriteInitial' => true,
        'showUpload' => false,
        'initialCaption' => (isset($params['image'])) ? $params['image'] : '',
        'initialPreviewConfig' => [],
        'overwriteInitial' => true
    ],
]);
?>
<br>
<label class="control-label" for="cmspageinfo-meta_title">Url</label>
<?= Html::textInput('url', $params['url'] ?? '', ['class' => 'form-control']) ?>
</div>