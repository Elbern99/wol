<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;


$this->title = Yii::t('app.menu', 'Homepage Commentary');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>
            <?php echo $form->field($model, 'video_ids')->widget(Select2::classname(), [
                'data' => $model::videosList(),
                'options' => ['placeholder' => 'Select videos for the page...', 'multiple' => true],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 10
                ],
            ])->label($model->getAttributeLabel('video_ids')); ?>
            <?php echo $form->field($model, 'opinion_ids')->widget(Select2::classname(), [
                'data' => $model::opinionsList(),
                'options' => ['placeholder' => 'Select opinions for the page...', 'multiple' => true],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 10
                ],
            ])->label($model->getAttributeLabel('opinion_ids')); ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
