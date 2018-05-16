<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<?php $this->beginContent('@frontend/views/emails/layout.php'); ?>
<p><span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Dear <?= Html::encode($user->fullName); ?>,</span></p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">Thank you for requesting this link to reset the password to your IZA World of Labor account.</span>
</p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">
        Please click <a href="<?= $resetLink; ?>" target="_blank">here</a> to reset your password.
    </span>
</p>

<p>
    <span style="font-size:10.0pt;font-family:'Lucida Sans',sans-serif">
        If you did not make this request then please let us know at <a href="mailto:wol.iza.org" target="_blank">wol@iza.org</a>.
    </span>
</p>
<?php //var_dump(Yii::getAlias('@common')); die('huj');?>
<?= $this->render('@frontend/views/emails/shared/footer-html.php', ['user' => $user, 'skipOptedIn' => true]); ?>

<?php $this->endContent(); ?>