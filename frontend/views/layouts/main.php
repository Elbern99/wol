<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="cleartype" content="on"/>
    <![endif]-->
    <meta name="HandheldFriendly" content="true"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- .preloader -->
    <div class="preloader">
        <div class="loading-ball"></div>
    </div><!-- / .preloader -->
    
    <main class="content">
        
        <?= $this->renderFile('@app/views/components/header/desktop/header.php'); ?>
        <?= $this->renderFile('@app/views/components/header/mobile/header.php'); ?>
        
        <?= $content ?>
        
        <?php $this->beginContent('@app/views/components/widgets.php'); ?><?php $this->endContent();?>

    </main>
  
  <footer class="footer">
      <div class="container">
            <div class="container-top">
                <?= $this->renderFile('@app/views/components/footer/footer.php'); ?>
            </div>
            <p class="copyright">
              Copyright &copy; IZA <?= date('Y') ?> <a href="#" target="_blank">Impressum</a>. All Rights Reserved. ISSN: 2054-9571
            </p>
      </div>
  </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
