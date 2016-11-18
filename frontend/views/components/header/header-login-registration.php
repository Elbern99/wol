<div class="login-registration">
    <ul class="login-registration-list">
        <li class="dropdown dropdown-login">
            <a href="#" class="dropdown-link"><?= Yii::t('app/menu', 'Login') ?></a>
            <div class="dropdown-widget drop-content">
                <div class="dropdown-widget-inner">
                    <form action="">
                        <div class="form-line">
                            <div class="label-holder">
                                <label for="email">Email address</label>
                            </div>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-line">
                            <div class="label-holder">
                                <label for="password">Password</label>
                            </div>
                            <input type="password" id="password" class="form-control" required>
                        </div>
                        <div class="buttons">
                            <button type="submit" class="btn-blue">login</button>
                            <a href="" class="forgot-link">forgot your password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </li>
        <li class="hide-mobile">
            <a href="#"><?= Yii::t('app/menu', 'register') ?></a>
        </li>
        <li class="hide-desktop dropdown">
            <a href="#" class="mobile-dropdown-link dropdown-link"><?= Yii::t('app/menu', 'register mobile') ?></a>
            <div class="dropdown-widget drop-content">
                <div class="dropdown-widget-inner">
                    <form action="">
                        <div class="form-line">
                            <div class="label-holder">
                                <label for="email">Email address</label>
                            </div>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-line">
                            <div class="label-holder">
                                <label for="password">Password</label>
                            </div>
                            <input type="password" id="password" class="form-control" required>
                        </div>
                        <div class="buttons">
                            <button type="submit" class="btn-blue">login</button>
                            <a href="" class="forgot-link">forgot your password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</div>