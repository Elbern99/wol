<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use frontend\components\filters\PressReleasesArchiveWidget;
?>
<?php
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

$pressReleasesArchiveWidget = PressReleasesArchiveWidget::widget(['data' => $pressReleasesArchive]);
?>
<div class="container news-list-page">
    <div class="article-head-holder">
        <div class="article-head">
            <div class="breadcrumbs">
                <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
            </div>
            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li class="active"><a href="" class="js-widget" data-linked="1">Latest press releases</a></li>
                    <li><a href="" class="js-widget">Press releases archives</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item active empty-nothing">
                    </div>
                    <div class="tab-item blue js-tab-hidden expand-more">
                        <?= $pressReleasesArchiveWidget ?>   
                    </div>
                </div>
            </div>
            <h1 class="hide-mobile"><?= Html::encode($page->Cms('title')) ?></h1>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text content-inner-animate active" data-linked="1">
            <h1 class="clone-title hide-desktop"><?= Html::encode($page->Cms('title')) ?></h1>
            <?= $page->Page('text'); ?>
            <?php Pjax::begin(['linkSelector' => '.btn-gray', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['class' => 'loader-ajax']]); ?>
            <ul class="post-list news-list">
                <?php foreach ($news as $item) : ?>
                <?php $hasImageClass = $item->image_link ? 'has-image' : null; ?>
                <li>
                    <div class="post-item <?= $hasImageClass; ?>">
                        <?php if ($hasImageClass) : ?>
                        <a href="/press-releases/<?= $item->url_key; ?>" class="img" style="background-image: url(<?= '/uploads/press-releases/'.$item->image_link; ?>)"></a>
                        <?php endif; ?>
                        <div class="desc">
                            <div class="inner">
                                <div class="head-news-holder">
                                    <div class="head-news">
                                        <div class="date">
                                            <?= $item->created_at->format('F d, Y'); ?>
                                        </div>
                                    </div>
                                </div>
                                <h2>
                                    <?= Html::a($item->title, ['/press-releases/view', 'slug' => $item->url_key]); ?>
                                </h2>
                                <div class="hide-mobile"><?= $item->short_description; ?></div>
                            </div>
                        </div>
                        <div class="hide-desktop"><?= $item->short_description; ?></div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php if ($newsCount > $limit): ?>
            <?php $params = [ 'limit' => $limit]; ?>
            <?= Html::a("show more", Url::current($params), ['class' => 'btn-gray align-center']) ?>
            <?php else: ?>
                <?php if (Yii::$app->request->get('limit')): ?>
                    <?= Html::a("clear", Url::current(), ['class' => 'btn-gray align-center']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php Pjax::end(); ?>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item hide-mobile is-open">
                        <a href="" class="title">press releases archives</a>
                        <div class="text">
                            <?= $pressReleasesArchiveWidget ?>   
                        </div>
                    </li>
                 </ul>
            </div>
            <?php if (count($page->getWidgets())): ?>
            <aside class="sidebar-widget">
                <?php foreach ($page->getWidgets() as $widget): ?>
                    <?= $widget['text'] ?>
                <?php endforeach; ?>
            </aside>
            <?php endif; ?>
        </aside>
    </div>
</div>