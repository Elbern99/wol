<?php

/* @var $this yii\web\View */
/* @var $page common\models\CmsPages */

use yii\helpers\Html;

$this->title = $page['meta_title'];
$this->params['breadcrumbs'][] = $this->title;

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($page['meta_keywords'])
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($page['meta_description'])
]);
?>
<div class="site-about">
    <h1><?= Html::encode($page['title']) ?></h1>
    
    <?php if (count($page['cmsPageSections'])): ?>
    <ul>
        <?php foreach ($page['cmsPageSections'] as $section): ?>

        <li <?= ($section['open']) ? 'class="open"' : '' ?>>
            <h3>
                <span class="toggle"></span>
                <a href="#<?= Html::encode($section['anchor']) ?>"><?= Html::encode($section['title']) ?></a>
            </h3>
            <div id="list-box">
                <?= $section['text'] ?>
            <div>
        </li>
        <?php endforeach; ?>
    </ul>
   <?php endif; ?>
</div>