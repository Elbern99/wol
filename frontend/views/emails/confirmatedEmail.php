<?php 
use yii\helpers\Html; 
use yii\helpers\Url;
?>

<?php
$params = ['/site/confirm/','token' => $token];
?>
<?php $this->beginContent(__DIR__.'/layout.php'); ?>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Dear <?= Html::encode($user->fullName); ?>,</span></p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">You have received this email because you signed up to IZA World of Labor.  To confirm your email address, <?= Html::a('<span style="color:black">please click here</span>', Url::to($params, true)); ?>.</span>
</p>

<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Best wishes, </span></p>
<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif;color:#1f497d">The IZA World of Labor team <span style="color:#1f497d"><br>
    <a href="mailto:wol.iza.org" target="_blank" style="color:black">wol.iza.org</a></span> – supporting evidence-based policy making </span>
</p>
<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif;">
        <a href="http://twitter.com/IZAWorldofLabor" target="_blank">twitter</a><br>
        <a href="http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452" target="_blank">facebook</a><br>
        <a href="https://www.linkedin.com/groups/6610789/profile" target="_blank">linkedin</a>
    </span>
</p>
<p><span style="font-size:11.0pt;font-family:'Calibri',sans-serif;color:#1f497d">&nbsp;</span></p>
<?php $this->endContent(); ?>
