<?php use yii\helpers\Url; ?>
<?php $this->beginContent(__DIR__.'/layout.php'); ?>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Welcome, </span></p>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">You have successfully created subscriber preferences for IZA World of Labor. You will receive an email alert when new articles are published in your chosen topic areas.
You can manage your article preferences here (<a href ="<?= $link ?>">link</a>).</span></p>

<p><span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">Questions? Please don’t hesitate to get in touch with us at <a href="mailto:wol@iza.org" target="_blank">wol@iza.org</a></span></p>

<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Best wishes, </span></p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif;color:#1f497d">T</span><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">he IZA World of Labor team <span style="color:#1f497d"><br>
    <a href="mailto:wol.iza.org" target="_blank" style="color:black">wol.iza.org </a></span> – supporting evidence-based policy making </span>
</p>
<p><span style="font-size:11.0pt;font-family:'Calibri',sans-serif;color:#1f497d">&nbsp;</span></p>
<p>
    <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">You are receiving this email to <span style="background:yellow"><a href="mailto:<?= \Yii::$app->params['moderatorEmail'] ?>" target="_blank"><?= \Yii::$app->params['moderatorEmail'] ?></a></span> as you are opted in to IZA World of Labor updates.<br>
    You can manage your IZA World of Labor contact details and preferences at 
    <span style="background:lime"><a href="<?= Url::to('/my-account', true) ?>" target="_blank"><?= Url::to('/my-account', true) ?></a></span>
    or
    <span style="background:lime"><a href="<?= Url::to(['/unsubscribe', 'number' => $subscriber->code], true) ?>" target="_blank">unsubscribe</a></span> from all IZA World of Labor emails. </span>
</p>
<?php $this->endContent(); ?>