<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/bootstrap-custom.min.css',
    ];
    public $js = [
    	'js/base.js',
    	'js/plugins/cookie_v2.1.3.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
