<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'My Account';
$this->params['breadcrumbs'][] = 'My Account';
$this->registerJsFile('/js/pages/signup.js', ['depends'=>['yii\web\YiiAsset']]);
$this->registerJsFile('/js/pages/my-account.js', ['depends'=>['yii\web\YiiAsset']]);
?>
<div class="content-inner my-account-page">
    <div class="content-inner-text">
        <div class="account-head-tabs">
            <div class="account-head-holder">
                <div class="container">
                    <div class="breadcrumbs">
                        <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
                    </div>

                    <div class="account-head">
                        <?= $this->renderFile(__DIR__.'/tabs/avatar.php', $tabs['account']['params']) ?>
                        <h1><?= $user->first_name ?></h1>
                    </div>
                </div>
            </div>
            <div class="account-tabs">
                <div class="container">
                    <a href="/my-account/delete" class="account-delete">delete account</a>
                    <ul class="account-tabs-list">
                        <li class="active"><a href="#tab-1">Your profile</a></li>
                        <li><a href="#tab-2">Favorite articles</a></li>
                        <li><a href="#tab-3">Saved searches</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="tabs-holder">
                <!-- .preloader -->
                <div class="preloader">
                    <div class="loading-ball"></div>
                </div>
                <!-- / .preloader -->
                <div class="tabs">
                    <?php foreach($tabs as $tab): ?>
                        <?php if (file_exists(__DIR__.'/'.$tab['path'])): ?>
                            <?= $this->renderFile(__DIR__.'/'.$tab['path'], $tab['params']) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
