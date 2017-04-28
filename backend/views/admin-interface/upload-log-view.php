<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\BottomMenu */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Log'), 'url' => Url::toRoute('/admin-interface/upload-log')];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="view">
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php if ($model->log): ?>
            <?php $logs = unserialize($model->getAttribute('log')); ?>
                <?php foreach($logs as $log): ?>
                    <p><?= $log ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div> 