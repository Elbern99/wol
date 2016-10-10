<?php

echo $form->field($node, 'meta_keywords')->textarea();
echo $form->field($node, 'description')->widget(\dosamigos\ckeditor\CKEditor::className(), [
    'options' => ['rows' => 8],
    'preset' => 'standard',
    'clientOptions'=>[
        'enterMode' => 2,
        'forceEnterMode'=>false,
        'shiftEnterMode'=>1
    ]
]);