<?php use yii\widgets\Breadcrumbs; ?>
<?=
Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => ['class'=>'breadcrumbs-list']
])
?>
