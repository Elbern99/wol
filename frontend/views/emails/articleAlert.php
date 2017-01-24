<?php use yii\helpers\Url; ?>
<?php $this->beginContent(__DIR__.'/layout.php'); ?>
<p>Welcome,
You have successfully created subscriber preferences for IZA World of Labor. You will receive an email alert when new articles are published in your chosen topic areas. 
You can manage your article preferences here (<a href ="<?= $link ?>">link</a>). 
</p>
<p>Questions? Please don’t hesitate to get in touch with us at wol@iza.org.</p>

<p>Best wishes,</p>

<p>The IZA World of Labor team
    wol.iza.org – supporting evidence-based policy making</p>
<ul class="socials-list">
    <li><a href="http://twitter.com/IZAWorldofLabor" target="_blank"><span class="icon-twitter"></span></a></li>
    <li><a href="http://www.linkedin.com/groups?gid=6610789&amp;mostPopular=&amp;trk=tyah&amp;trkInfo=tas%3AIZA%20wo%2Cidx%3A1-1-1" target="_blank"><span class="icon-linkedn"></span></a></li>
    <li><a href="http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452" target="_blank"><span class="icon-facebook"></span></a></li>
</ul>

<p>
You are receiving this email to XXX@XXX.com as you are opted in to IZA World of Labor updates. 
You can manage your IZA World of Labor contact details and preferences at <?= Url::to('/my-account', true) ?>
or <a href="<?= Url::to(['/unsubscribe', 'number' => $subscriber->code], true) ?>">unsubscribe</a>  from all IZA World of Labor emails.
</p>
<?php $this->endContent(); ?>