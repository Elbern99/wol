<div class="header-mobile-buttons">
    <div class="dropdown">
        <div class="btn-mobile-icon btn-mobile-login-show">
            <span class="icon-user"></span>
        </div>
        <div class="mobile-login drop-content">
            <div class="btn-mobile-icon btn-mobile-login-close"><span class="icon-cross dark"></span></div>
            <?php $this->beginContent('@app/views/components/header/header-login-registration.php'); ?><?php $this->endContent();?>
        </div>
    </div>
    <div class="dropdown">
        <div class="btn-mobile-icon btn-mobile-search-show">
            <span class="icon-search"></span>
        </div>
        <div class="mobile-search drop-content">
            <div class="btn-mobile-icon btn-mobile-search-close"><span class="icon-cross dark"></span></div>
            <?php $this->beginContent('@app/views/components/header/search-mobile.php'); ?><?php $this->endContent();?>
        </div>
    </div>
    <div class="dropdown">
        <div class="btn-mobile-icon btn-mobile-menu-show">
            <span class="icon-burger"><span></span></span>
        </div>
        <div class="mobile-menu drop-content">
            <div class="btn-mobile-icon btn-mobile-menu-close"><span class="icon-cross"></span></div>
            <div class="mobile-menu-section">
                <?php $this->beginContent('@app/views/components/header/header-menu-bottom.php'); ?><?php $this->endContent();?>
            </div>

            <div class="mobile-menu-section">
                <a href="">login</a>
                <a href="">register</a>
            </div>

            <div class="mobile-menu-section">
                <a href="">advanced search</a>
            </div>

            <div class="mobile-menu-section">
                <?php $this->beginContent('@app/views/components/header/header-menu-top.php'); ?><?php $this->endContent();?>
            </div>
        </div>
    </div>
	
</div>

<div class="header-mobile-menu-holder">
    
</div>