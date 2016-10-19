<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

Yii::$container->set('common\contracts\TypeInterface', 'common\components\Type');
Yii::$container->set('common\contracts\IUrlRewrite', 'common\models\UrlRewrite');

Yii::$container->setSingleton('Rewrite', function () {
    $rewrite = Yii::createObject('common\helpers\UrlRewriteHelper');
    return $rewrite;
});