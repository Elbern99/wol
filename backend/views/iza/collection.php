<?php

use yii\helpers\Html;

$this->title = Yii::t('app.menu', 'Attributes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Entity'), 'url' => $backLink];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php foreach ($collection->getEntity()->getValues() as $value): ?>
                <?php echo $value ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>