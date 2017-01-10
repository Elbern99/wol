<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\ckeditor\CKEditor;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Widget */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'News');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Newsletter News'), 'url' => Url::toRoute('/newsletter/news')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'date')->widget(
                    DatePicker::className(), [
                        'inline' => true,
                        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-M-yyyy'
                        ]
                ]);?>
            
                <?= $form->field($model, 'title') ?>

                <?= $form->field($model, 'main')->widget(CKEditor::className(), [
                    'options' => ['rows' => 50],
                    'preset' => 'standard',
                    'clientOptions'=> [
                        'allowedContent' => true,
                        'enterMode' => 2,
                        'forceEnterMode'=>false,
                        'shiftEnterMode'=>1
                    ]
                ]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
