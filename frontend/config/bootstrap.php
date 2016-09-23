<?php
Yii::$container->set('common\contracts\IUrlRewrite', 'common\models\UrlRewrite');

Yii::$container->setSingleton('Rewrite', function () {
    $rewrite = Yii::createObject('common\helpers\UrlRewriteHelper');
    return $rewrite;
});

