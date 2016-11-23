<?php use kartik\file\FileInput; ?>
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
</div>