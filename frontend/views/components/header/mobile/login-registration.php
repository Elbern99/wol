<div class="login-registration logged">
    <ul class="login-registration-list">
        <li class="dropdown dropdown-login">
            <a href="#" class="dropdown-link"><?= Yii::t('app/menu', 'Login') ?></a>
            <div class="dropdown-widget drop-content">
                <form action="../../../../../backend/web/index.php">
                    <div class="form-line">
                        <div class="label-holder">
                            <label for="email_id_desktop">Email address</label>
                        </div>
                        <input type="email" id="email_id_desktop" class="form-control" required>
                    </div>
                    <div class="form-line">
                        <div class="label-holder">
                            <label for="password_id_mobile">Password</label>
                        </div>
                        <input type="password" id="password_id_mobile" class="form-control" required>
                    </div>
                    <div class="buttons">
                        <button type="submit" class="btn-blue">login</button>
                        <a href="" class="forgot-link">forgot your password?</a>
                    </div>
                </form>
            </div>
        </li>
        <li class="hide-mobile">
            <a href="#"><?= Yii::t('app/menu', 'register') ?></a>
        </li>
        <li class="hide-desktop dropdown">
            <a href="#" class="mobile-dropdown-link dropdown-link"><?= Yii::t('app/menu', 'register') ?></a>
            <div class="dropdown-widget">
                test
            </div>
        </li>
    </ul>
</div>