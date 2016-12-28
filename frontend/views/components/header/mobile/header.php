<header class="header header-mobile">
    <div class="header-top">
        <div class="container">
            <a href="/" class="logo-main">
                <img src="<?= common\modules\settings\SettingsRepository::get('logo') ?>" alt="IZA World of Labor" title="IZA World of Labor" />
            </a>
            <?= $this->renderFile('@app/views/components/header/mobile/buttons.php'); ?>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <?= $this->renderFile('@app/views/components/header/menu/main.php'); ?>
        </div>
    </div>
</header>