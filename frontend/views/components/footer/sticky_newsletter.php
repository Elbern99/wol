<?php 
use frontend\components\blocks\StickyNewsletter;

$block = new StickyNewsletter(Yii::$container->get('blocks'));
echo $block->getView();


