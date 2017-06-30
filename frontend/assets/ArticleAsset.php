<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ArticleAsset extends AssetBundle
{
    public $jsOptions = ['position' => \yii\web\View::POS_END, 'charset' => 'utf-8'];
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/leaflet.css',
    ];
    public $js = [
    	'js/plugins/scrollend.js',
        'js/plugins/share-text.js',
        'js/pages/article.js',
        'js/pages/keywords-search.js',
        'js/plugins/leaflet.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
