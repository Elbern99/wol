<?php use yii\helpers\Html; ?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <?php $this->registerCssFile("/css/site_email.css"); ?>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <p>
        <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">You are receiving this email to<span style="background:yellow"><a href="mailto:anna.fleming@bloomsbury.com" target="_blank">anna.fleming@bloomsbury.com</a></span> as you are opted in to IZA World of Labor updates.<br>
        You can manage your IZA World of Labor contact details and preferences at <span style="background:lime"><a href="<?= Url::to('/my-account', true) ?>" target="_blank">http://iza.lokomotiv.cloud/my-account</a></span> or <span style="background:lime"><a href="<?= Url::to(['/unsubscribe', 'number' => $subscriber->code], true) ?>" target="_blank">unsubscribe</a></span> from all IZA World of Labor emails. </span>
    </p>
    <p>
        <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">Contact <span style="color:#1f497d"> u</span>s: <a href="mailto:wol@iza.org" target="_blank">wol@iza.org</a> - IZA World of Labor, Forschungsinstitut zur Zukunft der Arbeit GmbH (IZA), Schaumburg-Lippe-Strasse 5-9, 53113 Bonn, Germany.</span>
    </p>
    <p>
        <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">Copyright © IZA <span style="background:yellow">2017</span></span>
    </p>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>