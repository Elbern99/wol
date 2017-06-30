<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class MapAsset extends AssetBundle
{
    public $jsOptions = ['position' => \yii\web\View::POS_END, 'charset' => 'utf-8'];
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/leaflet.css',
    ];
    public $js = [
        'js/plugins/share-text.js',
        'js/pages/map.js',
        'js/pages/keywords-search.js',
        'js/plugins/leaflet.js',
        'js/plugins/icon.label.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
