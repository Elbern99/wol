<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\UrlRewrite */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Url Rewrite'), 'url' => Url::toRoute('/url-rewrite')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'current_path') ?>
            <?= $form->field($model, 'rewrite_path') ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>