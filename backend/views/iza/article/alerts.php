<?php

use yii\bootstrap\Html;

?>
<div class="col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('app', 'Article enabled alerts') ?></h3>
        </div>
        <div class="panel-body">
            <?= Html::button(Yii::t('app/form', 'Send'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('app', 'Article new version alerts') ?></h3>
        </div>
        <div class="panel-body">
            <?= Html::button(Yii::t('app/form', 'Send'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>


