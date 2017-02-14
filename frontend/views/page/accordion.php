<?php
/* @var $this yii\web\View */
/* @var $page common\models\Page */

use yii\helpers\Html;
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.$page->Cms('meta_title');
$this->params['breadcrumbs'][] = Html::encode($page->Cms('title'));

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($page->Cms('meta_keywords'))
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($page->Cms('meta_description'))
]);
?>

<div class="container">
    <div class="breadcrumbs">
        <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
    </div>
    <h1><?= Html::encode($page->Cms('title')) ?></h1>
    <div class="content-inner">
        <?php if (count($page->getPage())): ?> 
            <div class="content-inner-text">
                <ul class="faq-accordion-list">
                    <?php foreach ($page->getPage() as $tab): ?>
                        <li class="faq-accordion-item <?= ($tab['open']) ? 'is-open' : '' ?>">
                            <a href='#<?= $tab['anchor'] ?>' class='title'>
                                <h3><?= $tab['title'] ?></h3>
                            </a>
                            <div class="text">
                                <div class="text-inner">
                                    <?= $tab['text'] ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (count($page->getWidgets())): ?>
            <aside class="sidebar-right">
                <?php foreach ($page->getWidgets() as $widget): ?>
                    <?= $widget['text'] ?>
                <?php endforeach; ?>
            </aside>
        <?php endif; ?>
    </div>
</div>
