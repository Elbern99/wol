<?php 
use yii\helpers\Html; 
use yii\helpers\Url;
?>

<?php
$params = (!is_null($newEmail)) ? ['/site/confirm/','token' => $token, 'email' => $newEmail] : ['/site/confirm/','token' => $token];
?>
<?php $this->beginContent(__DIR__.'/layout.php'); ?>
<p><?= $user->first_name ?> <?= $user->last_name ?></p>
<p><?= Html::a('To confirm click here!' , Url::to($params, true)); ?></p>
<?php $this->endContent(); ?>