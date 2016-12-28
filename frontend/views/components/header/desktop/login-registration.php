<?php use yii\helpers\Html ?>
<div class="login-registration">
    <?php if (Yii::$app->user->isGuest) : ?>
    <ul class="login-registration-list">
        <li class="dropdown dropdown-login">
            <?= $this->renderFile('@app/views/site/login.php'); ?>
        </li>
        <li class="hide-mobile">
            <a href="/register"><?= Yii::t('app/menu', 'register') ?></a>
        </li>
    </ul>
    <?php else: ?>
    <ul class="login-registration-list logout">
        <li class="dropdown dropdown-login">
            <a href ="/my-account" class="welcome-link">Welcome, <strong><?= Yii::$app->user->identity->first_name ?? '' ?></strong></a>
        </li>
        <li class="hide-mobile">
            <?= Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout',
                ['class' => 'logout-link']
            )
            . Html::endForm() ?>
        </li>
    </ul>
    <?php endif; ?>
</div>