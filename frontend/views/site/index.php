<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\articles\SubjectAreas;
use yii\widgets\Pjax;
use yii\helpers\Url;

use common\models\Video;
use common\models\Opinion;

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

<div class="home-page">
    <div class="header-background" style="background-image: url('<?= $page->Page('backgroud') ?>');"></div>
    <div class="container without-breadcrumbs">
        <div class="body-content">

            <div class="content-inner">
                <div class="content-inner-text">

                    <div class="index-head">
                       <?= $page->Page('text') ?> 
                    </div>

                    <?= $page->getWidgetByName('home_featured_article') ?>
                    
                    <div class="articles-list-holder">
                        <?php Pjax::begin(['linkSelector' => '#article_limit_button','options' => ['class' => 'loader-ajax']]); ?>
                        <div class="widget-title medium"><a href="/articles">latest articles</a></div>
                        <ul class="post-list home-articles-list">
                            <?php foreach($collection as $article): ?>
                            <li class="post-item">
                                <ul class="article-rubrics-list">
                                    <?php foreach($article['category'] as $link): ?>
                                        <li><?= $link ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <h2><a href="<?= $article['url'] ?>"><?= $article['title'] ?></a></h2>
                                <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
                                <div class="publish">
                                    <?php foreach($article['authors'] as $author): ?><?= $author ?><?php endforeach; ?>, <?= date('F Y', $article['created_at']) ?></div>
                                <div class="description">
                                    <?= $article['abstract']->abstract ?? ''; ?>
                                </div>
                                <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?= $more->getLink('article_limit') ?>
                        <?php Pjax::end(); ?>
                    </div>
                    
                    <?php Pjax::begin(['linkSelector' => '#news_limit_button', 'options' => ['class' => 'loader-ajax']]); ?>
                    <div class="news-list-holder">
                        <div class="widget-title medium"><a href="/news">latest news</a></div>
                        <ul class="post-list news-home-list">
                            <?php foreach ($news as $newsItem): ?>
                            <?php $img = ($newsItem['image_link']) ? true : false; ?>
                            <li>
                                <div class="post-item <?= ($img) ? 'has-image' : '' ?>">
                                    <?php if ($img): ?>
                                    <a href="<?= Url::to(['/news/'.$newsItem['url_key']]) ?>" class="img" style="background-image: url(/uploads/news/<?= $newsItem['image_link'] ?>)"></a>
                                    <?php endif; ?>
                                    <div class="desc">
                                        <div class="head-news-holder">
                                            <div class="head-news">
                                                <div class="date"><?= date('M d, Y', strtotime($newsItem['created_at'])) ?></div>
                                                <div class="publish">
                                                    <?= $newsItem['editor'] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <h2>
                                            <a href="<?= Url::to(['/news/'.$newsItem['url_key']]) ?>"><?= $newsItem['title'] ?></a>
                                        </h2>
                                        <div class="hide-mobile"><?= $newsItem['short_description'] ?></div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?= $more->getLink('news_limit') ?>
                    </div>
                    <?php Pjax::end(); ?>
                    <?php if ($commentary) : ?>
                    <div class="other-commentary-list-holder">
                        <?php /* Show Commentary*/ ?>
                        <div class="widget-title medium"><a href="/commentary">commentary</a></div>
                        <ul class="post-list other-commentary-list">
                            <?php foreach ($commentary as $item) : ?>
                            <li class="post-item media-item">
                                <?php if ($item->type == Opinion::class) : ?>
                                    <?php $opinion = Opinion::find()->where(['id' => $item->object_id])->one(); ?>
                                    <?php if ($opinion) : ?>
                                    <?php $hasImage = $opinion->image_link ? true : false; ?>
                                    <?php if ($hasImage) : ?>
                                    <?= Html::beginTag('a', [
                                        'href' => Url::to(['/opinion/view', 'slug' => $opinion->url_key]),
                                        'class' => 'img',
                                        'style' => "background-image: url('/uploads/opinions/".$opinion->image_link."')",
                                    ]); ?>
                                    <?= Html::endTag('a'); ?>
                                    <?php else : ?>
                                    <?= Html::beginTag('a', [
                                        'href' => Url::to(['/opinion/view', 'slug' => $opinion->url_key]),
                                        'class' => 'img',
                                        'style' => "background-image: url('')",
                                    ]); ?>
                                    <?= Html::endTag('a'); ?>
                                    <?php endif; ?>
                                    <div class="category">
                                        <?= Html::a('opinion', ['/opinion/index']); ?>
                                    </div>
                                    <div class="author"><?= $opinion->getAuthorsLink(); ?></div>
                                    <h2>
                                        <?= Html::a($opinion->title, ['/opinion/view', 'slug' => $opinion->url_key]); ?>
                                    </h2>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($item->type == Video::class) : ?>
                                    <?php $video = Video::find()->where(['id' => $item->object_id])->one(); ?>
                                    <?php if ($video) : ?>
                                    <?= Html::beginTag('a', [
                                        'href' => Url::to(['/video/view', 'slug' => $video->url_key]),
                                        'class' => 'img',
                                        'style' => "background-image: url('".$video->getVideoImageLink()."')",
                                    ]); ?>
                                    <span class="icon-play"></span>
                                    <?= Html::endTag('a'); ?>
                                    <div class="category"><?= Html::a('video', ['/video/index']); ?></div>
                                    <h2>
                                        <?= Html::a($video->title, ['/video/view', 'slug' => $video->url_key]); ?>
                                    </h2>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <?php Pjax::begin(['linkSelector' => '#event_limit_button', 'options' => ['class' => 'loader-ajax']]); ?>
                    <div class="other-events-list-holder">
                        
                        <div class="widget-title medium"><a href="/events">events</a></div>
                        <ul class="post-list home-other-events-list media-list">
                            <?php foreach ($events as $event): ?>
                            <li class="post-item media-item">
                                <div class="date"><?= date('M d, Y', strtotime($event['date_from'])) ?> - <?= date('M d, Y', strtotime($event['date_to'])) ?></div>
                                <h2>
                                    <?= Html::beginTag('a', ['href' => Url::to(['/event/view', 'slug' => $event['url_key']])]) ?>
                                    <?= $event['title'] ?>
                                    <?= Html::endTag('a') ?>
                                </h2>
                                <p><?= $event['location'] ?></p>
                                <!--<div class="location">Daniel S. Hamermesh</div>-->
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?= $more->getLink('event_limit') ?>
                    </div>
                    <?php Pjax::end(); ?>
                </div>

                <aside class="sidebar-right">
                    <div class="sidebar-widget hide-mobile">
                        <div class="widget-title">articles by subject</div>
                        <?= SubjectAreas::widget(['category' => $subjectAreas]) ?>
                    </div>
                    <div class="sidebar-widget clone-topics-widget">
                        <div class="clone-topics">
                            <div class="widget-title">trending topics</div>
                            <div class="data-method-list">
                                <?php foreach ($topics as $topic): ?>
                                <a href="<?= Url::to(['/topic/view', 'slug' => $topic['url_key']]) ?>" class="data-method-item gray">
                                    <div class="img"><img src="<?= Url::to('/uploads/topics/' . $topic['image_link']) ?>" alt=""></div>
                                    <div class="caption">
                                        <span class="icon-arrow-square-blue">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </span>
                                        <h3><?= $topic['title'] ?></h3>
                                    </div>
                                </a>
                                <?php endforeach; ?> 
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-widget sidebar-widget-subscribe">
                        <div class="widget-title">iza world of labor on twitter</div>
                        <a class="twitter-timeline custom-tr" data-lang="en" data-dnt="true" data-chrome="noheader transparent nofooter" data-tweet-limit="5" data-cards="hidden" data-theme="light" data-link-color="#0053a0" href="https://twitter.com/<?= common\modules\settings\SettingsRepository::get('twitter_feed_id') ?>">
                            Tweets by IZAWorldofLabor
                        </a>
                        <?= $this->registerJsFile('/js/plugins/twitter.js', ['async' => 'async', 'charset' => 'utf-8']) ?>
                        <a href="https://twitter.com/<?= common\modules\settings\SettingsRepository::get('twitter_feed_id') ?>" class="twitter-follow-button" data-size="large" data-show-count="false">Follow @IZAWorldofLabor</a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>

<?= $page->getWidgetByName('sticky_newsletter') ?>
