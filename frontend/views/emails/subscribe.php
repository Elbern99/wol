<?php use yii\helpers\Url; ?>
<?php $this->beginContent(__DIR__.'/layout.php'); ?>
    <p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Welcome,</span></p>

    <p>
        <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">You have successfully subscribed to the IZA World of Labor newsletter. We look forward to keeping you updated with the latest labor market news and research. </span>
    </p>

    <p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Best wishes, </span></p>

    <p>
        <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif;color:#1f497d">T</span>
        <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">he IZA World of Labor team <span style="color:#1f497d"><br>
        <a href="<?= $link ?>" target="_blank">wol.iza.org </a></span> – supporting evidence-based policy making </span>
    </p>
<?php $this->endContent(); ?>