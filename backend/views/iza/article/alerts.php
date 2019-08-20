<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

/** @var $article \common\models\Article */

$this->registerJsFile(Url::to(['/js/article/article-alerts.js']), ['depends' => [JqueryAsset::className()]]);

?>
<div class="col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('app', 'Article enabled alerts') ?></h3>
        </div>
        <div class="panel-body">
            <?= Html::button(Yii::t('app/form', 'Send'), [
                'id'    => 'new-article-alerts-btn',
                'class' => 'btn btn-primary send-alerts',
                'data-confirm-message' => Yii::t('app', 'Are you sure to send alerts?'),
                'data-url' => Url::to(['newsletter/send-new-article-alerts', 'articleId' => $article->id], true)
            ]) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('app', 'Article new version alerts') ?></h3>
        </div>
        <div class="panel-body">
            <?= Html::button(Yii::t('app/form', 'Send'), [
                'id'    => 'new-version-alerts-btn',
                'class' => 'btn btn-primary send-alerts',
                'data-confirm-message' => Yii::t('app', 'Are you sure to send alerts?'),
                'data-url' => Url::to(['newsletter/send-new-article-alerts', 'articleId' => $article->id], true)
            ]) ?>
        </div>
    </div>
</div>


