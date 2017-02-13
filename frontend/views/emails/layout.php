<?php use yii\helpers\Url; ?>
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
        <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">Contact <span style="color:#1f497d"> u</span>s: <a href="mailto:wol@iza.org" target="_blank">wol@iza.org</a> - IZA World of Labor, Forschungsinstitut zur Zukunft der Arbeit GmbH (IZA), Schaumburg-Lippe-Strasse 5-9, 53113 Bonn, Germany.</span>
    </p>
    <p>
        <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">Copyright &copy; IZA 2017/span>
    </p>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>