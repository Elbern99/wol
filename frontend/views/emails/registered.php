<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $subscriber int */
?>
    
<?php $this->beginContent(__DIR__.'/layout.php'); ?>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Dear <?= Html::encode($user->fullName); ?>,</span></p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">You have successfully created your IZA World of Labor account. To manage your preferences and view your saved articles, just sign into your account.</span>
</p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">
        Questions? Please don’t hesitate to get in touch with us at <a href="mailto:wol.iza.org" target="_blank">wol@iza.org</a>.
    </span>
</p>

<?= $this->render('@frontend/views/emails/shared/footer-html.php', ['user' => $user, 'subscriber' => $subscriber]); ?>

<?php $this->endContent(); ?>
