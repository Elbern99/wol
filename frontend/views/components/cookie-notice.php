<?php 
use frontend\components\blocks\HeaderCookieNotice;

$block = new HeaderCookieNotice(Yii::$container->get('blocks'));
echo $block->getView();
