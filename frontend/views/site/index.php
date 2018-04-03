<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\articles\SubjectAreas;
use yii\widgets\Pjax;
use yii\helpers\Url;
use frontend\assets\TwitterAsset;
use common\models\NewsItem;

$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.$page->Cms('meta_title');
$this->params['breadcrumbs'][] = Html::encode($page->Cms('title'));
$googleVerification = common\modules\settings\SettingsRepository::get('google_site_verification');

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($page->Cms('meta_keywords'))
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($page->Cms('meta_description'))
]);

if ($googleVerification) {
    $this->registerMetaTag([
        'name' => 'google-site-verification',
        'content' => $googleVerification
    ]);
}

TwitterAsset::register($this);
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
                        <?php Pjax::begin(['linkSelector' => '#article_limit_button', 'options' => ['class' => 'loader-ajax']]); ?>
                        <div class="widget-title medium"><a href="/articles">latest articles</a></div>
                        <ul class="post-list home-articles-list">
                            <?php foreach ($collection as $article): ?>
                                <li>
                                    <div class="post-item">
                                        <ul class="article-rubrics-list">
                                            <?php foreach ($article['category'] as $link): ?>
                                                <li><?= $link ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <h2><a href="<?= $article['url'] ?>"><?= $article['title'] ?></a></h2>
                                        <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
                                        <div class="writers">
                                            <?php foreach ($article['authors'] as $author): ?><span class="writer-item"><?= $author ?></span><?php endforeach; ?>, <?= date('F Y', $article['created_at']) ?>
                                        </div>
                                        <div class="description">
                                            <?= $article['abstract']->abstract ?? ''; ?>
                                        </div>
                                        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                                    </div>
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
                                            <a href="<?= Url::to(['/news/' . $newsItem['url_key']]) ?>" class="img" style="background-image: url(/uploads/news/<?= $newsItem['image_link'] ?>)"></a>
                                        <?php endif; ?>
                                        <div class="desc">
                                            <div class="head-news-holder">
                                                <div class="head-news">
                                                    <div class="date"><?= date('M d, Y', strtotime($newsItem['created_at'])) ?></div>
                                                    <div class="writers">
                                                        <span class="writer-item"><?= NewsItem::getSourcesLink($newsItem['sources']) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <h2>
                                                <a href="<?= Url::to(['/news/' . $newsItem['url_key']]) ?>"><?= $newsItem['title'] ?></a>
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
                    <?php if (count($commentary['video']) || $commentary['opinion']) : ?>
                        <div class="other-commentary-list-holder">
                            <?php /* Show Commentary */ ?>
                            <div class="widget-title medium"><a href="/commentary">commentary</a></div>
                            <ul class="post-list other-commentary-list">
                                <?php foreach ($commentary['video'] as $video): ?>
                                    <li><div class="post-item s-opinion-item media-item">
                                            <?=
                                            Html::beginTag('a', [
                                                'href' => Url::to(['/video/view', 'slug' => $video->url_key]),
                                                'class' => 'img',
                                                'style' => "background-image: url('" . $video->getVideoImageLink() . "')",
                                            ]);
                                            ?>
                                            <span class="icon-play"></span>
                                            <?= Html::endTag('a'); ?>
                                            <div class="category"><?= Html::a('video', ['/video/index']); ?></div>
                                            <h2><?= Html::a($video->title, ['/video/view', 'slug' => $video->url_key]); ?></h2>
                                        </div></li>
                                <?php endforeach; ?>

                                <?php foreach ($commentary['opinion'] as $opinion): ?>
                                    <li><div class="post-item s-opinion-item media-item">
                                            <?php $hasImage = $opinion->image_link ? true : false; ?>
                                            <?php if ($hasImage) : ?>
                                                <?=
                                                Html::beginTag('a', [
                                                    'href' => Url::to(['/opinion/view', 'slug' => $opinion->url_key]),
                                                    'class' => 'img',
                                                    'style' => "background-image: url('/uploads/opinions/" . $opinion->image_link . "')",
                                                ]);
                                                ?>
                                                <?= Html::endTag('a'); ?>
                                            <?php else : ?>
                                                <?=
                                                Html::beginTag('a', [
                                                    'href' => Url::to(['/opinion/view', 'slug' => $opinion->url_key]),
                                                    'class' => 'img',
                                                    'style' => "background-image: url('')",
                                                ]);
                                                ?>
                                                <?= Html::endTag('a'); ?>
                                            <?php endif; ?>

                                            <div class="category"><?= Html::a('opinion', ['/opinion/index']); ?></div>
                                            <?php if (count($opinion['opinionAuthors'])): ?>
                                                <div class="author">
                                                    <?=
                                                    implode(', ', array_map(
                                                            function($item) {
                                                            $author = $item['author_name'];

                                                            if ($item['author_url']) {
                                                                return Html::a($item['author_name'], $item['author_url']);
                                                            }

                                                            return $author;
                                                        }, $opinion['opinionAuthors']
                                                        )
                                                    )
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                            <h2><?= Html::a($opinion->title, ['/opinion/view', 'slug' => $opinion->url_key]); ?></h2>
                                        </div></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php Pjax::begin(['linkSelector' => '#event_limit_button', 'options' => ['class' => 'loader-ajax']]); ?>
                    <div class="other-events-list-holder">
                        <div class="widget-title medium"><a href="/events">events</a></div>
                        <ul class="post-list home-other-events-list media-list">
                            <?php foreach ($events as $event): ?>
                                <li>
                                    <div class="post-item media-item">
                                        <?php if (date('M d, Y', strtotime($event['date_from'])) != date('M d, Y', strtotime($event['date_to']))) : ?>
                                            <div class="date"><?= date('M d, Y', strtotime($event['date_from'])) ?> - <?= date('M d, Y', strtotime($event['date_to'])) ?></div>
                                        <?php else : ?>
                                            <div class="date"><?= date('M d, Y', strtotime($event['date_from'])) ?></div>
                                        <?php endif; ?>
                                        <h2>
                                            <?= Html::beginTag('a', ['href' => Url::to(['/event/view', 'slug' => $event['url_key']])]) ?>
                                            <?= $event['title'] ?>
                                            <?= Html::endTag('a') ?>
                                        </h2>
                                        <p><?= $event['location'] ?></p>
                                        <div class="hide-mobile">
                                            <p><?= $event['short_description']; ?></p>
                                        </div>
                                    </div>
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
                                        <div class="img"><img src="<?= Yii::$app->imageCache->getImgSrc('/uploads/topics/' . $topic['image_link'], '297', '239') ?>" alt=""></div>
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
                    <div class="sidebar-widget sidebar-widget-twitter">
                        <div class="widget-title">iza world of labor on twitter</div>
                        <a data-lang="en" data-tweet-limit="5" class="twitter-timeline custom-tr" data-chrome="noheader transparent nofooter" href="https://twitter.com/<?= common\modules\settings\SettingsRepository::get('twitter_feed_id') ?>" data-link-color="#0053a0">Tweets by IZAWorldofLabor</a>
                        <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
                        <?php
                        /*
                        <a class="twitter-timeline custom-tr" data-lang="en" data-dnt="true" data-chrome="noheader transparent nofooter" data-tweet-limit="5" data-cards="hidden" data-theme="light" data-link-color="#0053a0" href="https://twitter.com/<?= common\modules\settings\SettingsRepository::get('twitter_feed_id') ?>">
                            Tweets by IZAWorldofLabor
                        </a>
                            */
                        ?>
                        <a href="https://twitter.com/<?= common\modules\settings\SettingsRepository::get('twitter_feed_id') ?>" class="twitter-follow-button" data-size="large" data-show-count="false">Follow @IZAWorldofLabor</a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>
