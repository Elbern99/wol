
<header class="header header-desktop">
    <nav>
        <ul class="screen_reader_link_menu">
            <li>
                <a href="#screen_reader_content" class="screen_reader_btn">Skip to main content</a>
            </li>
        </ul>
    </nav>
    <div class="header-top">
        <div class="container">
            <?= $this->renderFile('@app/views/components/header/menu/top.php'); ?>
            <?= $this->renderFile('@app/views/components/header/desktop/login-registration.php'); ?>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <a href="/" class="logo-main">
                <?= common\modules\settings\SettingsRepository::get('logo') ?>
            </a>

            <?= $this->renderFile('@app/views/components/header/menu/main.php'); ?>
            <?= $this->renderFile('@app/views/search/header_search.php'); ?>
        </div>
    </div>
</header>