<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use backend\helpers\ConsoleRunner;

/** @var $article \common\models\Article */

$this->registerJsFile(Url::to(['/js/article/article-alerts.js']), ['depends' => [JqueryAsset::className()]]);

?>
<div class="col-sm-12">
    <div id="article-newsletter-alerts"></div>
</div>

<div class="col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('app', 'Newsletter "Article from IZA World of Labor"') ?></h3>
        </div>
        <div class="panel-body">
            <?= Html::button(Yii::t('app/form', 'Send to queue'), [
                'id'    => 'new-article-alerts-btn',
                'class' => 'btn btn-primary send-alerts',
                'data-confirm-message' => Yii::t('app', 'Are you sure to send alerts?'),
                'data-url' => Url::to(['newsletter/send-new-article-alerts', 'articleId' => $article->id], true),
                'disabled' => ConsoleRunner::isRun('yii alerts/new-article-alerts ' .$article->id) ? true : false
            ]) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('app', 'Newsletter "A new version of an article published"') ?></h3>
        </div>
        <div class="panel-body">
            <?= Html::button(Yii::t('app/form', 'Send to queue'), [
                'id'    => 'new-version-alerts-btn',
                'class' => 'btn btn-primary send-alerts',
                'data-confirm-message' => Yii::t('app', 'Are you sure to send alerts?'),
                'data-url' => Url::to(['newsletter/send-new-version-article-alerts', 'articleId' => $article->id], true),
                'disabled' => ConsoleRunner::isRun('yii alerts/new-article-version-alerts' .$article->id) ? true : false
            ]) ?>
        </div>
    </div>
</div>


