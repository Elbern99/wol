<?php
use yii\helpers\Html;
use kartik\tabs\TabsX;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => 'CMS', 'url' => Url::toRoute('/cms')];
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