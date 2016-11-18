<header class="header header-desktop">
	<div class="header-top">
		<div class="container">
			<?php $this->beginContent('@app/views/components/header/header-menu-top.php'); ?><?php $this->endContent();?>
			<?php $this->beginContent('@app/views/components/header/header-login-registration.php'); ?><?php $this->endContent();?>
		</div>
	</div>
	<div class="header-bottom">
		<div class="container">
			<a href="" class="logo-main">
				<img src="../images/logo-main.svg" alt="IZA World of Labor" title="IZA World of Labor" />
			</a>
			
			<?php $this->beginContent('@app/views/components/header/header-menu-bottom.php'); ?><?php $this->endContent();?>
			<?php $this->beginContent('@app/views/components/header/search-desktop.php'); ?><?php $this->endContent();?>
		</div>
	</div>
</header>