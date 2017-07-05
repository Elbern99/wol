<?php

?>
<?= $this->renderFile('@app/views/components/footer/sticky_newsletter.php'); ?>
<div class="footer-left">
    <div class="footer-logos-list">
        <div class="item">
            <a href="/" class="logo-main">
                <?= common\modules\settings\SettingsRepository::get('logo') ?>
            </a>
        </div>
        <div class="item">
            <?= common\modules\settings\SettingsRepository::get('second_logo') ?>
        </div>
    </div>
</div>

<div class="footer-middle">
    <div class="socials-title">Follow IZA WORLD OF LABOR</div>
    <div class="socials">
        <ul class="socials-list">
            <li><a href="http://twitter.com/IZAWorldofLabor" target="_blank"><span class="icon-twitter"></span></a></li>
            <li><a href="http://www.linkedin.com/groups?gid=6610789&mostPopular=&trk=tyah&trkInfo=tas%3AIZA%20wo%2Cidx%3A1-1-1" target="_blank"><span class="icon-linkedn"></span></a></li>
            <li><a href="http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452" target="_blank"><span class="icon-facebook"></span></a></li>
            <li><a href="https://plus.google.com/116017394173863766515" target="_blank"><span class="icon-google"></span></a></li>
        </ul>
    </div>
</div>

<div class="footer-right">
    <?= \common\modules\menu\Menu::$bottom; ?>
</div>
