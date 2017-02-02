<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
$this->registerJsFile('/js/plugins/scrollpane.js', ['depends' => ['yii\web\YiiAsset']]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="content-type" content="text/html; charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="cleartype" content="on"/>
    <![endif]-->
    <meta name="HandheldFriendly" content="true"/>
    <?= Html::csrfMetaTags() ?>
    <title id="title-document"><?= Html::encode($this->title) ?></title>
    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="holder">

<div class="wrapper">
    <div class="overlay js-tab-hidden"></div>

    <main class="content">
        
        <?= $this->renderFile('@app/views/components/header/desktop/header.php'); ?>
        <?= $this->renderFile('@app/views/components/header/mobile/header.php'); ?>

        <?= $content ?>
        
        <?php $this->beginContent('@app/views/components/widgets.php'); ?><?php $this->endContent();?>

    </main>
  
  <footer class="footer">
      <div class="footer-inner">
          <div class="container">
              <div class="container-top">
                  <?= $this->renderFile('@app/views/components/footer/footer.php'); ?>
              </div>
              <p class="copyright">
                  Copyright &copy; IZA <?= date('Y') ?> <a href="https://www.iza.org/imprint" target="_blank">Impressum</a>. <br>All Rights Reserved. ISSN: 2054-9571
              </p>
          </div>
      </div>
  </footer>
</div>

<?php $this->endBody() ?>

</div>
</body>
</html>
<?php $this->endPage() ?>
