<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $items int id common\models\Modules */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'Cms Type');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-lg-12">
    <?php $form = ActiveForm::begin([
              'enableClientValidation' => true,
              'enableAjaxValidation' => false,
              'method' => 'post',
              'action' => Url::to([$postUrl])
          ]); 
    ?>
    
    <div class="form-group field-menulinks-type required has-success">
        <label class="control-label" for="menulinks-type"><?php Yii::t('app/form', 'Type') ?></label>
        <?= Html::listBox('cms_type', null, $items, ['size' => 1]) ?>
    </div>
   
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>