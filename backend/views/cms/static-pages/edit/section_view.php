<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\CmsPageSections */
/* @var $page int id common\models\CmsPage */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Static Page Sections'), 'url' => Url::toRoute(['/cms/static-pages-view', 'id'=>$page])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-lg-12">
    <?php $form = ActiveForm::begin([
              'enableClientValidation' => true,
              'enableAjaxValidation' => false,
              'method' => 'post'
          ]); 
    ?>

    <?= $form->field($model, 'title') ?>
    <?= $form->field($model, 'anchor') ?>
    <?= $form->field($model, 'order') ?>
    <?= $form->field($model, 'text')->widget(CKEditor::className(), [
        'options' => ['rows' => 8],
        'preset' => 'standard',
        'clientOptions'=>[
            'enterMode' => 2,
            'forceEnterMode'=>false,
            'shiftEnterMode'=>1
        ]
    ]) ?>
    <?= $form->field($model, 'open')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>