<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use frontend\components\filters\NewsletterArchiveWidget;
use frontend\components\filters\NewsletterLatestArticlesWidget;
use frontend\components\filters\NewsArchiveWidget;
?>

<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'News';
$this->params['breadcrumbs'][] = 'News';

if ($category) {
    $this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($category->meta_keywords)
    ]);
    $this->registerMetaTag([
        'name' => 'title',
        'content' => Html::encode($category->meta_title)
    ]);
}

$newsletterArchiveWidget = NewsletterArchiveWidget::widget(['data' => $newsletterArchive]);
$latestArticlesWidget = NewsletterLatestArticlesWidget::widget(['data' => $latestArticles]);
$newsArchiveWidget = NewsArchiveWidget::widget(['data' => $newsArchive]);
?>
<div class="container news-list-page">
    <div class="article-head-holder">
        <div class="article-head">
            <div class="breadcrumbs">
                <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
            </div>
            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li class="active">
                        <a href="/news" data-linked="1">Latest news</a>
                    </li>
                    <li><a href="" class="js-widget">News archives</a></li>
                    <li><a href="" class="js-widget">Newsletters</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item active empty-nothing"></div>
                    <div class="tab-item blue js-tab-hidden expand-more">
                        <?= $newsArchiveWidget ?>
                    </div>
                    <div class="tab-item blue js-tab-hidden expand-more">
                        <?= $newsletterArchiveWidget; ?>
                    </div>
                </div>
            </div>
            <h1 class="hide-mobile">News from IZA World of Labor</h1>
            <p class="hide-mobile">We draw on a range of global sources to bring you news related to IZA World of Labor articles.</p>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text content-inner-animate active" data-linked="1">
            <?php if ($isInArchive) : ?>
            <h1 class="clone-title hide-desktop">News from IZA World of Labor</h1>
            <?php else: ?>
            <h1 class="clone-title hide-desktop">Latest news from IZA World of Labor</h1>
            <?php endif; ?>
            <?php Pjax::begin(['linkSelector' => '.btn-gray', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['class' => 'loader-ajax']]); ?>
            <ul class="post-list news-list">
                <?php foreach ($news as $item) : ?>
                <?php $hasImageClass = $item->image_link ? 'has-image' : null; ?>
                <li>
                    <div class="post-item <?= $hasImageClass; ?>">
                        <?php if ($hasImageClass) : ?>
                        <a href="/news/<?= $item->url_key; ?>" class="img" style="background-image: url(<?= '/uploads/news/'.$item->image_link; ?>)"></a>
                        <?php endif; ?>
                        <div class="desc">
                            <div class="inner">
                                <div class="head-news-holder">
                                    <div class="head-news">
                                        <div class="date">
                                            <?= $item->created_at->format('F d, Y'); ?>
                                        </div>
                                        <?php if ($item->editor) : ?>
                                        <div class="writers">
                                            <span class="writer-item">
                                                <?= $item->editor; ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <h2>
                                    <?= Html::a($item->title, ['/news/view', 'slug' => $item->url_key]); ?>
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
                    <?= Html::a("clear", Url::to(['/news/index']), ['class' => 'btn-gray align-center']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php Pjax::end(); ?>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item hide-mobile is-open">
                        <a href="" class="title">news archives</a>
                        <div class="text">
                            <?= $newsArchiveWidget; ?>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item hide-mobile">
                        <a href="" class="title">newsletters</a>
                        <div class="text">
                           <?= $newsletterArchiveWidget; ?>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Latest Articles</a>
                        <div class="text">
                            <?= $latestArticlesWidget; ?>
                        </div>
                    </li>
                 </ul>
            </div>
            <?php if (count($widgets)): ?>
                <div class="sidebar-widget">
                    <div class="podcast-list">
                        <?php foreach ($widgets->getPageWidgets() as $widget): ?>
                            <?= $widget ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</div>