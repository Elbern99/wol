<div class="header-mobile-buttons">
    <div class="dropdown">
        <div class="btn-mobile-icon btn-mobile-login-show">
            <span class="icon-user"></span>
        </div>
        <div class="mobile-login drop-content">
            <div class="btn-mobile-icon btn-mobile-login-close"><span class="icon-cross dark"></span></div>
            <?= $this->renderFile('@app/views/components/header/login-registration.php'); ?>
        </div>
    </div>
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
                <a href=""><?= Yii::t('app/menu', 'login') ?></a>
                <a href=""><?= Yii::t('app/menu', 'register') ?></a>
            </div>

            <div class="mobile-menu-section">
                <a href="">advanced search</a>
            </div>

            <div class="mobile-menu-section">
                <?= $this->renderFile('@app/views/components/header/menu/top.php'); ?>
            </div>
        </div>
    </div>
</div>

