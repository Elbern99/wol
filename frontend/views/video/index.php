<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>

<?php

$this->title = 'Videos';
$this->params['breadcrumbs'][] = ['label' => Html::encode('Commentary'), 'url' => Url::to(['/commentary'])];
$this->params['breadcrumbs'][] = $this->title;

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
$this->registerJsFile('/js/pages/opinions.js', ['depends' => ['yii\web\YiiAsset']]);
?>

<div class="container videos-page">
    <div class="article-head-holder">
        <div class="article-head article-head-full">
            <div class="breadcrumbs">
                <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
            </div>
            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li><a href="/opinions" class="">Opinions</a></li>
                    <li class="active"><a href="javascript:void(0)">Videos</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item js-tab-hidden expand-more">
                        <ul class="sidebar-news-list">
                            <?php foreach ($opinionsSidebar as $opinion) : ?> 
                            <li>
                                <h3>
                                    <?= Html::a($opinion->title, ['opinion/view', 'slug' => $opinion->url_key]); ?>
                                </h3>
                                <div class="writer"><?= $opinion->getAuthorsLink(); ?></div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if (count($opinionsSidebar) > Yii::$app->params['opinion_sidebar_limit']): ?>
                        <a href="" class="more-link">
                            <span class="more">More</span>
                            <span class="less">Less</span>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="tab-item js-tab-hidden expand-more">
                        <ul class="sidebar-news-list">
                            <?php foreach ($videosSidebar as $video) : ?> 
                            <li>
                                <h3>
                                    <?= Html::a($video->title, ['/video/view', 'slug' => $video->url_key]); ?>
                                </h3>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if (count($videosSidebar) > Yii::$app->params['video_sidebar_limit']): ?>
                        <a href="" class="more-link">
                            <span class="more">More</span>
                            <span class="less">Less</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <h1>Videos</h1>
            <div class="more-text-mobile">
                <p>Watch exclusive video from conferences, debates and other events on labor market economics, contributions from IZA World of Labor authors, and more. </p>
                <a href="" class="more-evidence-map-text-mobile"><span class="more">More</span><span class="less">Less</span></a>
            </div>
        </div>
    </div>

    <div class="content-inner">
        <?php Pjax::begin(['linkSelector' => '.btn-gray', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['class' => 'loader-ajax']]); ?>
        <div class="content-inner-text">
            <ul class="videos-list">
                <?php foreach ($videos as $video) : ?>
                <li>
                    <div class="video-item has-image">
                        <?= Html::beginTag('a', [
                            'href' => Url::to(['/video/view', 'slug' => $video->url_key]),
                            'class' => 'img',
                            'style' => "background-image: url('".$video->getVideoImageLink()."')",
                        ]); ?>
                        <span class="icon-play"></span>
                        <?= Html::endTag('a'); ?>
                        <div class="desc">
                            <div class="inner">
                                <h2>
                                     <?= Html::a($video->title, ['/video/view', 'slug' => $video->url_key]); ?>
                                </h2>
                                <?php if ($video->description) : ?>
                                <p>
                                   <?= $video->description; ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php if ($videosCount > $limit): ?>
                    <?php $params = ['/video/index', 'limit' => $limit]; ?>
                    <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray align-center']) ?>
            <?php else: ?>
                <?php if (Yii::$app->request->get('limit')): ?>
                     <?php $params = ['/video/index']; ?>
                    <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray align-center']) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php Pjax::end(); ?>

        <aside class="sidebar-right hide-mobile">
            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">opinions</a>
                        <div class="text is-open">
                            <ul class="sidebar-news-list">
                                <?php foreach($opinionsSidebar as $opinion) : ?>
                                <li>
                                    <h3>
                                        <?= Html::a($opinion->title, ['/opinion/view', 'slug' => $opinion->url_key]); ?>
                                    </h3>
                                    <div class="writer"><?= $opinion->getAuthorsLink(); ?></div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (count($opinionsSidebar) > Yii::$app->params['opinion_sidebar_limit']): ?>
                            <a href="" class="more-link">
                                <span class="more">More</span>
                                <span class="less">Less</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">videos</a>
                        <div class="text is-open">
                            <ul class="sidebar-news-list">
                                <?php foreach ($videosSidebar as $video) : ?>
                                <li>
                                    <h3>
                                         <?= Html::a($video->title, ['/video/view', 'slug' => $video->url_key]); ?>
                                    </h3>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (count($videosSidebar) > Yii::$app->params['video_sidebar_limit']): ?>
                            <a href="" class="more-link">
                                <span class="more">More</span>
                                <span class="less">Less</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
</div>