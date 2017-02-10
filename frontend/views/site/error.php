<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

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
                We're sorry the page you have requested does not exist or has moved.
            </p>
            <p>
                Please return to the <a href="/">Homepage</a>, to continue to browse IZA World of Labor.
            </p>
            <p>
                Email us to <ins>report a broken link</ins> <a href="mailto:wol@iza.org" target="_blank">wol@iza.org</a> and we'll fix it.
            </p>
        </div>
    </div>
</div>
