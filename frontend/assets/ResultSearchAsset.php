<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ResultSearchAsset extends AssetBundle
{
    public $jsOptions = ['position' => \yii\web\View::POS_END, 'async' => 'async', 'charset' => 'utf-8'];
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/plugins/jquery.mark.min.js',
        'js/pages/advanced-search.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
