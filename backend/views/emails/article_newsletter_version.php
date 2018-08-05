<?php use yii\helpers\Url; ?>

<?php $this->beginContent(__DIR__.'/layout.php'); ?>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Dear subscriber,</span></p>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">A new version of an article has been published on IZA World of Labor in a topic you are interested in:
<?= $article->title ?> by <?= $article->availability ?> <a href="<?= Url::to('/'.$article->url, true) ?>"><?= Url::to('/'.$article->url, true) ?></a>
<a href="<?= Url::to($article->pdf, true) ?>">Download as PDF</span></a>
</p>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">You can view a summary of the changes made by clicking on the Information icon in the ‘Versions’ panel of each article.</span></p>

<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Know someone who would also be interested in this article? Click the forward button and share with them.</span></p>

<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">You can manage your article alert preferences at my account.</span></p>

<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Best wishes, </span></p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif;color:#1f497d">The IZA World of Labor team <span style="color:#1f497d"><br>
    <a href="mailto:wol.iza.org" target="_blank" style="color:black">wol.iza.org </a></span> – supporting evidence-based policy making </span>
</p>
<p><span style="font-size:11.0pt;font-family:'Calibri',sans-serif;color:#1f497d">&nbsp;</span></p>
<p>
    <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">You are receiving this email to <a href="mailto:<?= $subscriber->email ?>" target="_blank"><?= $subscriber->email ?></a> as you are opted in to IZA World of Labor updates.<br>
    You can manage your IZA World of Labor contact details and preferences at my account
    or
    <a href="<?= Url::to('/unsubscribe?number='.$subscriber->code, true) ?>" target="_blank">unsubscribe</a> from all IZA World of Labor emails. </span>
</p>
<p>
    <span style="font-size:8.0pt;font-family:'Lucida Sans',sans-serif">Contact <span style="color:#1f497d"> u</span>s: <a href="mailto:wol@iza.org" target="_blank">wol@iza.org</a> - IZA World of Labor, Forschungsinstitut zur Zukunft der Arbeit GmbH (IZA), Schaumburg-Lippe-Strasse 5-9, 53113 Bonn, Germany.</span>
</p>
<?php $this->endContent(); ?>

