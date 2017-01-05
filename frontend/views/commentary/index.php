<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<?php
$this->title = 'Commentary';
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
?>

<div class="container commentary-page">
    <div class="breadcrumbs">
        <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
    </div>
    <div class="mobile-filter-holder custom-tabs-holder">
        <ul class="mobile-filter-list">
            <?php if ($opinions) : ?>
            <li><a href="" class="js-widget">Opinions</a></li>
            <?php endif; ?>
            <?php if ($hasVideo) : ?>
            <li><a href="" class="js-widget">Videos</a></li>
            <?php endif; ?>
        </ul>
        <div class="mobile-filter-items custom-tabs">
            <?php if ($opinions) : ?>
            <div class="tab-item js-tab-hidden expand-more">
                <ul class="sidebar-news-list">
                    <?php foreach ($opinionsSidebar as $opinion) : ?>
                    <li>
                        <h3>
                            <?= Html::a($opinion->title, ['/opinion/view', 'slug' => $opinion->url_key]); ?>
                        </h3>
                        <div class="writer">Hardcoded Author</div>
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
            <?php endif; ?>
            <?php if ($hasVideo) : ?>
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
            <?php endif; ?>
        </div>
    </div>
    <h1>Commentary</h1>
    <p>Watch exclusive video from conferences, debates and other events on labor market economics as well as reading the latest opinion pieces from IZA World of Labor authors.</p>
    <?php if ($opinions) : ?>
    <div class="widget-title medium"><?= Html::a('opinions', ['/opinion/index']); ?></div>
    <?php Pjax::begin(['linkSelector' => '#load-opinions', 'enableReplaceState' => false, 'enablePushState' => false]); ?>
    <ul class="commentary-opinions-list">
        <?php foreach ($opinions as $opinion) : ?>
        <?php $hasImageClass = $opinion->image_link ? 'has-image' : null; ?>
        <li>
            <div class="opinion-item <?= $hasImageClass; ?>">
                <?php if ($hasImageClass) : ?>
                 <a href="<?= '/opinions/'. $opinion->url_key; ?>" title="<?= $opinion->title ?>" class="img" style="background-image: url(<?= '/uploads/opinions/'.$opinion->image_link; ?>)"></a>
                <?php endif; ?>
                <div class="desc">
                    <div class="inner">
                        <div class="date"><?= $opinion->created_at->format('F d, Y'); ?></div>
                        <div class="name"><a href="">Hardcoded Author</a></div>
                        <h2><?= Html::a($opinion->title, ['/opinion/view', 'slug' => $opinion->url_key]); ?></h2>
                        <?php if ($opinion->short_description) : ?>
                            <p>
                                <?= $opinion->short_description; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </li>
       <?php endforeach; ?>
    </ul>
    <?php if ($opinionsCount > $opinionLimit): ?>
            <?php $params = ['/commentary/opinions', 'opinion_limit' => $opinionLimit]; ?>
            <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-opinions']) ?>
    <?php else: ?>
        <?php if (Yii::$app->request->get('opinion_limit')): ?>
             <?php $params = ['/commentary/opinions']; ?>
            <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-opinions']) ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php Pjax::end(); ?>
    <?php endif; ?>
    <?php if ($hasVideo) : ?>
    <div class="widget-title medium"><?= Html::a('videos', ['/video/index']); ?></div>
    <?php Pjax::begin(['linkSelector' => '#load-videos', 'enableReplaceState' => false, 'enablePushState' => false]); ?>
    <ul class="commentary-videos-list">
        <?php foreach ($videos as $video) : ?>
        <li class="">
            <div class="opinion-item has-image">
                
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
    <?php if ($videosCount > $videoLimit): ?>
            <?php $params = ['/commentary/videos', 'video_limit' => $videoLimit]; ?>
            <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray show-more align-center videos', 'id' => 'load-videos']) ?>
    <?php else: ?>
        <?php if (Yii::$app->request->get('video_limit')): ?>
             <?php $params = ['/commentary/videos']; ?>
            <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-videos']) ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php Pjax::end(); ?>
    <?php endif; ?>
</div>