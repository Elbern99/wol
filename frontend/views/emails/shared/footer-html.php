<?php
use yii\helpers\Html;
use yii\helpers\Url;

if (!isset($subscriber)) {
    $subscriber = $user->getSubscriber() ? $user->getSubscriber()->getAttribute('code') : false;
}

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>

<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Best wishes, </span></p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif;color:#1f497d">T</span><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">he IZA World of Labor team <span style="color:#1f497d"><br>
    <a href="https://wol.iza.org" target="_blank" style="color:black">https://wol.iza.org</a></span> – supporting evidence-based policy making </span>
</p>
<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif;">
        <a href="http://twitter.com/IZAWorldofLabor" target="_blank">twitter</a><br>
        <a href="http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452" target="_blank">facebook</a><br>
        <a href="https://www.linkedin.com/groups/6610789/profile" target="_blank">linkedin</a>
    </span>
</p>
<p><span style="font-size:11.0pt;font-family:'Calibri',sans-serif;color:#1f497d">&nbsp;</span></p>
<?php if ($subscriber): ?>
<p>
    <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">
        <?php if (!isset($skipOptedIn) || $skipOptedIn !== true) : ?>
        You are receiving this email to <a href="mailto:<?= $user->email ?>" target="_blank"><?= $user->email ?></a> as you are opted in to IZA World of Labor updates.
        <br>
        <?php endif; ?>
        
        You can manage your IZA World of Labor contact details and preferences at <a href="<?= Url::to(['my-account/index'], true); ?>" target="_blank">my account</a> 
    or <a href="<?= Url::to(['/unsubscribe', 'number' => $subscriber], true) ?>" target="_blank">unsubscribe</a> from all IZA World of Labor emails. </span>
</p>
<?php endif; ?>
<p>
    <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">
    Contact us: <a href="https://wol.iza.org/contact" target="_blank" style="color:black">https://wol.iza.org</a> – IZA World of Labor, Forschunginstitut zur Zukunft der Arbeit GmbH (IZA), Schaumburg-Lippe-Strasse 5-9, 53113 Bonn, Germany. 
    </span>
</p>
<p>
    <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">
    Copyright @IZA <?= date('Y'); ?>
    </span>
</p>