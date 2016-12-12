<header class="header header-desktop">
    <div class="header-top">
        <div class="container">
            <?= $this->renderFile('@app/views/components/header/menu/top.php'); ?>
            <?= $this->renderFile('@app/views/components/header/desktop/login-registration.php'); ?>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <a href="/" class="logo-main">
                <img src="<?= common\modules\settings\SettingsRepository::get('logo') ?>" alt="IZA World of Labor" title="IZA World of Labor" />
            </a>

            <?= $this->renderFile('@app/views/components/header/menu/main.php'); ?>
            <?= $this->renderFile('@app/views/components/header/desktop/search.php'); ?>
        </div>
    </div>
</header>