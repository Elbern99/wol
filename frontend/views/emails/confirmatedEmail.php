<?php 
use yii\helpers\Html; 
use yii\helpers\Url;
?>

<?php
$params = (!is_null($newEmail)) ? ['/site/confirm/','token' => $token, 'email' => $newEmail] : ['/site/confirm/','token' => $token];
?>
<?php $this->beginContent(__DIR__.'/layout.php'); ?>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Dear <?= $user->first_name ?> <?= $user->last_name ?></span></p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">You have received this email because you signed up to IZA World of Labor.  To confirm your email address,<?= Html::a('<span style="color:black">please click here</span>' , Url::to($params, true)); ?></span>
</p>

<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Best wishes, </span></p>
<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif;color:#1f497d">T</span>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">he IZA World of Labor team <span style="color:#1f497d"><br>
    <a href="" target="_blank" style="color:black">wol.iza.org </a></span> – supporting evidence-based policy making </span>
</p>
<?php $this->endContent(); ?>