<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php

$this->title = $model->title;
$this->params['breadcrumbs'][] =['label'=>'News', 'url'=>Url::to('/news', true)];
$this->params['breadcrumbs'][] = $this->title;


$this->registerMetaTag([
'name' => 'keywords',
'content' => Html::encode($this->title)
]);
$this->registerMetaTag([
    'name' => 'title',
    'content' => Html::encode($this->title)
]);

?>
<div class="breadcrumbs">
    <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
</div>
<?= $model->main ?>

