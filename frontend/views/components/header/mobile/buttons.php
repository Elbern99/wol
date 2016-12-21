<div class="header-mobile-buttons">
    <?php if (Yii::$app->user->isGuest) : ?>
    <div class="dropdown">
        <div class="btn-mobile-icon btn-mobile-login-show">
            <span class="icon-user"></span>
        </div>
        <div class="mobile-login drop-content">
            <div class="btn-mobile-icon btn-mobile-login-close"><span class="icon-cross dark"></span></div>
            <?= $this->renderFile('@app/views/components/header/mobile/login-registration.php'); ?>
        </div>
    </div>
    <?php else: ?>
    <div class="dropdown">
        <a href="/my-account" class="btn-mobile-icon">
            <span class="icon-user"></span>
        </a>
    </div>
    <?php endif; ?>

    <div class="dropdown">
        <div class="btn-mobile-icon btn-mobile-search-show">
            <span class="icon-search"></span>
        </div>
        <div class="mobile-search drop-content">
            <div class="btn-mobile-icon btn-mobile-search-close"><span class="icon-cross dark"></span></div>
            <?= $this->renderFile('@app/views/search/header_search.php'); ?>
        </div>
    </div>
    <div class="dropdown">
        <div class="btn-mobile-icon btn-mobile-menu-show">
            <span class="icon-burger"><span></span></span>
        </div>
        <div class="mobile-menu drop-content">
            <div class="mobile-menu-section">
                <?= $this->renderFile('@app/views/components/header/menu/main.php'); ?>
            </div>
            <div class="mobile-menu-section">

                <?php if (Yii::$app->user->isGuest) : ?>
                <a href="" class="open-mobile-login"><?= Yii::t('app/menu', 'login') ?></a>
                <a href="" class="open-mobile-register"><?= Yii::t('app/menu', 'register') ?></a>
                <?php else: ?>
                <a href="/my-account"><?= Yii::t('app/menu', 'my-account') ?></a>
                <?php endif; ?>
            </div>
            <div class="mobile-menu-section">
                <a href="/search/advanced">advanced search</a>
            </div>
            <div class="mobile-menu-section">
                <?= $this->renderFile('@app/views/components/header/menu/top.php'); ?>
            </div>
        </div>
    </div>
</div>

