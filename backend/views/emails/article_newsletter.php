<?php use yii\helpers\Url; ?>

<?php $this->beginContent(__DIR__.'/layout.php'); ?>
<p>Dear subscriber,</p>
<p>An article has been published on IZA World of Labor in a topic you are interested in: 
<?= $article->title ?> by <?= $article->availability ?> <a href="<?= Url::to('/'.$article->url, true) ?>"><?= Url::to('/'.$article->url, true) ?></a>
<a href="<?= Url::to($article->pdf, true) ?>">Download as PDF</a>
</p>
<p>Know someone who would also be interested in this article? Click the forward button and share with them.</p>

<p>You can manage your article alert preferences here (<a href ="<?= Url::to('/my-account', true) ?>">link</a>).</p>

<p>Best wishes,</p>

<p>The IZA World of Labor team
wol.iza.org – supporting evidence-based policy making
<ul class="socials-list">
    <li><a href="http://twitter.com/IZAWorldofLabor" target="_blank"><span class="icon-twitter"></span></a></li>
    <li><a href="http://www.linkedin.com/groups?gid=6610789&amp;mostPopular=&amp;trk=tyah&amp;trkInfo=tas%3AIZA%20wo%2Cidx%3A1-1-1" target="_blank"><span class="icon-linkedn"></span></a></li>
    <li><a href="http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452" target="_blank"><span class="icon-facebook"></span></a></li>
</ul>
</p>
<p>
    You are receiving this email to XXX@XXX.com as you are opted in to IZA World of Labor updates. 
    You can manage your IZA World of Labor contact details and preferences at <?= Url::to('/my-account', true) ?> or 
    <a href="<?= Url::to(['/unsubscribe', 'number' => $subscriber->id], true) ?>">unsubscribe</a>  from all IZA World of Labor emails.
</p>
<p>
Contact Us:
wol@iza.org - IZA World of Labor, Forschungsinstitut zur Zukunft der Arbeit GmbH (IZA), Schaumburg-Lippe-Strasse 5-9, 53113 Bonn, Germany.
</p>
<?php $this->endContent(); ?>