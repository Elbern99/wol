<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AdvancedSearchAsset extends AssetBundle
{
    public $jsOptions = ['position' => \yii\web\View::POS_END];
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/jquery.tagit.css',
        'css/plugins/tagit.ui-zendesk.css'
    ];
    public $js = [
    	'js/plugins/jqueryui.min.js',
        'js/plugins/tag-it.min.js',
        'js/pages/advanced-search.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
