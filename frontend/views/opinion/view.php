<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<?php
$this->registerJsFile('/js/plugins/share-buttons.js', ['depends' => ['yii\web\YiiAsset']]);

$opinionDirectLink = Url::to(['/opinion/view', 'slug' => $model->url_key], true);
$mailLink = $opinionDirectLink;

$mailTitle = $model->title;
$mailBody = 'Hi.\n\n I think that you would be interested in the  following article from IZA World of labor. \n\n  Title: '. $mailTitle .
    '\n\n View the article: '. Html::a($mailLink, $mailLink). '\n\n Copyright © IZA 2016'.'Impressum. All Rights Reserved. ISSN: 2054-9571';

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
$this->params['breadcrumbs'][] = ['label' => Html::encode('Commentary'), 'url' => Url::to(['/event/index'])];
$this->params['breadcrumbs'][] = ['label' => Html::encode('Opinions'), 'url' => Url::to(['/opinion/index'])];
$this->params['breadcrumbs'][] = $model->title;
?>

<div class="container single-post-page">
    <div class="article-head-holder">
        <div class="article-head">
            <div class="breadcrumbs">
                <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
            </div>
           <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li><a href="" class="js-widget">Opinions</a></li>
                    <li><a href="" class="js-widget">Videos</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
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
                    <div class="tab-item js-tab-hidden expand-more">
                        <ul class="sidebar-news-list">
                            <?php foreach ($videosSidebar as $video) : ?>
                            <li>
                                <h3>
                                    <a href="#">
                                        <?= $video->title; ?>
                                    </a>
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
            <h1><?= $model->title; ?></h1>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <article class="post-full-item">
                <?php $hasImage= $model->image_link ? true : false; ?>
                <?php if ($hasImage) : ?>
                <figure>
                    <?= Html::img('@web/uploads/opinions/'.$model->image_link, [
                        'alt' => 'Opinion image',
                    ]); ?>
                </figure>
                <?php endif; ?>
                <p>
                    <?= $model->description; ?>
                </p>
            </article>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-buttons-holder">
                <ul class="share-buttons-list">
                    <li class="share-facebook">
                        <div id="fb-root"></div>
                        <div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div>
                    </li>
                    <li class="share-twitter">
                        <a class="twitter-share-button" href="https://twitter.com/intent/tweet">Tweet</a>
                    </li>
                    <li class="share-ln">
                        <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                        <script type="IN/Share"></script>
                    </li>
                </ul>

                <div class="sidebar-email-holder">
                    <a href="mailto:?subject=<?= Html::encode('Article from IZA World of Labor') ?>&body=<?= Html::encode($mailBody) ?>" class="btn-border-gray-small with-icon-r">
                        <div class="inner">
                            <span class="icon-message"></span>
                            <span class="text">email</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">opinions</a>
                        <div class="text is-open">
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
                    </li>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">videos</a>
                        <div class="text is-open">
                            <ul class="sidebar-news-list">
                                <?php foreach ($videosSidebar as $video) : ?>
                                <li>
                                    <h3>
                                        <a href="#"><?= $video->title; ?></a>
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

            <div class="sidebar-widget">
                <div class="podcast-list">
                    <?php foreach ($widgets as $widget): ?>
                        <?= $widget['text'] ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>
    </div>
</div>