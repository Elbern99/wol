<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>
<div class="container site-error without-breadcrumbs error-page">
    <div class="content-inner">
        <div class="content-inner-text">
            <div class="article-head">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>

            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>

            <p>
                We're sorry the page you have requested cannot be found or has moved.
            </p>
            <p>
                Please return to the <a href="/">Homepage</a>,
                use the navigation above or <a href="<?= Url::to('/search/advanced',true); ?>">search</a> to find what you are looking for.
            </p>
            <p>
                Email us to report a <a href="mailto:wol@iza.org" target="_blank">broken link</a> and we'll fix it.
            </p>
        </div>
    </div>
</div>
