<?php
/* @var $this yii\web\View */
/* @var $page common\models\Page */

use yii\helpers\Html;

$breadcrumbs = unserialize($page->Cms('breadcrumbs'));
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.$page->Cms('meta_title');

if (is_array($breadcrumbs) && count($breadcrumbs)) {
    $this->params['breadcrumbs'] = $breadcrumbs;
}

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

<?php if ($page->Page('backgroud')): ?>
    <div class="header-background" style="background-image: url('<?= $page->Page('backgroud') ?>');"></div>
<?php endif; ?>
<div class="container">

    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
        <h1><?= Html::encode($page->Cms('title')) ?></h1>
    </div>

    <div class="content-inner">
        <div class="content-inner-text contact-page">
            <?= $page->Page('text'); ?>
        </div>

        <?php if (count($page->getWidgets())): ?>
            <aside class="sidebar-right">
                <?php foreach ($page->getWidgets() as $widget): ?>
                    <?= $widget['text'] ?>
                <?php endforeach; ?>
            </aside>
        <?php endif; ?>
    </div>
</div>