<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <p>
        <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">Copyright &copy; IZA <span style="background:yellow">2017</span></span>
    </p>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>