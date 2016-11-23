<?php 

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="col-lg-12">
    <?php
    $form = ActiveForm::begin([
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'method' => 'post',
                'action' => $postUrl,
                'options' => ['enctype' => 'multipart/form-data']
    ]);
    ?>
    
    <?= $type; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/text', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    
<?php ActiveForm::end(); ?>
</div>