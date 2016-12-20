<?php use yii\helpers\Html ?>
<div class="login-registration logged">
    <?php if (Yii::$app->user->isGuest) : ?>
    <ul class="login-registration-list">
        <li class="dropdown dropdown-login">
            <?= $this->renderFile('@app/views/site/login.php'); ?>
        </li>
        <li class="hide-mobile">
            <a href="#"><?= Yii::t('app/menu', 'register') ?></a>
        </li>
        <li class="hide-desktop dropdown">
            <a href="#" class="mobile-dropdown-link dropdown-link"><?= Yii::t('app/menu', 'register') ?></a>
            <div class="dropdown-widget">
            </div>
        </li>
    </ul>
    <?php else: ?>
    <ul class="login-registration-list">
        <li class="dropdown dropdown-login">
            <a href ="/my-account">Welcome, <?= Yii::$app->user->identity->first_name ?? '' ?></a>
        </li>
        <li class="hide-mobile">
            <?= Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout',
                ['class' => '']
            )
            . Html::endForm() ?>
        </li>
    </ul>
    <?php endif; ?>
</div>