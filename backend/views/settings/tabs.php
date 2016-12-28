<?php
use yii\helpers\Html;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $items array */

$this->title = Yii::t('app.menu', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="row content">
        <div class="col-sm-12 sidenav">
           <?=
           TabsX::widget([
               'items' => $items,
               'position' => TabsX::POS_LEFT,
               'encodeLabels' => false
           ]);
           ?>
        </div>
    </div>
</div>