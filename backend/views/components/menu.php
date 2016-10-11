<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => 'IZA World of Labor',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$menuItems = [];

if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
    
    $menuItems[] = ['label' => Yii::t('app/menu','Home'), 'url' => ['/site/index']];
    
    $menuItems[] = ['label' =>  Yii::t('app/menu','CMS'),
        'url' => ['#'],
        'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
        'items' => [
            ['label' => Yii::t('app/menu','Static Pages'), 'url' => ['/cms/static-pages']]
        ],
    ];
    
    $menuItems[] = ['label' => Yii::t('app/menu','Category'), 'url' => ['/category']];
    
    $menuItems[] = ['label' =>  Yii::t('app/menu','URL Redirects'), 'url' => ['/url-rewrite']];
    
    $menuItems[] = ['label' => Yii::t('app/menu','Menu'),
        'url' => ['#'],
        'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
        'items' => [
            ['label' => Yii::t('app/menu','Bottom Menu'), 'url' => ['/menu/bottom']]
        ],
    ];
    
    $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();
