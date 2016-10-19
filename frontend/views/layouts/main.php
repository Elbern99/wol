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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php $this->beginContent('@app/views/components/header.php'); ?><?php $this->endContent();?>
    <?php $this->beginContent('@app/views/components/menu.php'); ?><?php $this->endContent();?>

    <div class="container">
        <?php $this->beginContent('@app/views/components/widgets.php'); ?><?php $this->endContent();?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <?php $this->beginContent('@app/views/components/footer.php'); ?><?php $this->endContent();?>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
