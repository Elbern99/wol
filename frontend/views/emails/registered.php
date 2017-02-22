<?php use yii\helpers\Url; ?>
<?php $this->beginContent(__DIR__.'/layout.php'); ?>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Welcome,</span></p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">You have successfully created your IZA World of Labor account. To manage your preferences and view your saved articles, just sign into your account.</span>
</p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">
        Questions? Please don’t hesitate to get in touch with us at <a href="mailto:wol.iza.org" target="_blank">wol@iza.org</a>.
    </span>
</p>

<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Best wishes, </span></p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif;color:#1f497d">T</span><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">he IZA World of Labor team <span style="color:#1f497d"><br>
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
<?php if ($subscriber): ?>
<p>
    <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">You are receiving this email to <a href="mailto:<?= $user->email ?>" target="_blank"><?= $user->email ?></a> as you are opted in to IZA World of Labor updates.<br>
    You can manage your IZA World of Labor contact details and preferences at my account or <a href="<?= Url::to(['/unsubscribe', 'number' => $subscriber], true) ?>" target="_blank">unsubscribe</a> from all IZA World of Labor emails. </span>
</p>
<?php endif; ?>
<?php $this->endContent(); ?>