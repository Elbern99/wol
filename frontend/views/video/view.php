<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
    $this->registerJsFile('/js/plugins/share-buttons.js', ['depends' => ['yii\web\YiiAsset']]);

$mailLink = Url::to(['/opinion/view', 'slug' => $model->url_key], true);
$mailTitle = 'title';
$mailBody = 'Hi.\n\n I think that you would be interested in the  following article from IZA World of labor. \n\n  Title: '. $mailTitle .
    '\n\n View the video: '.  Html::a($mailLink, $mailLink) . '\n\n Copyright © IZA 2016'.'Impressum. All Rights Reserved. ISSN: 2054-9571';

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

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => Html::encode('Commentary'), 'url' => Url::to(['/event/index'])];
$this->params['breadcrumbs'][] = ['label' => Html::encode('Videos'), 'url' => Url::to(['/video/index'])];
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
                                    <?=  Html::a($opinion->title, ['/opinion/view', 'slug' => $opinion->url_key]); ?>
                                </h3>
                                <div class="writer">Augustin De Coulon</div>
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
                            <?php foreach ($videosSidebar as $video): ?>
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
            <h1>Dawn or Doom: The effects of Brexit on immigration, wages, and employment</h1>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text contact-page">
            <article class="post-full-item">
                <figure>
                    <?= Html::tag('iframe', null, [
                        'width' => 560,
                       // 'height' => 315,
                        'src' => $model->video,
                        'frameboarder' => 0,
                        'allowfullscreen' => true, 
                    ]); ?>
                </figure>
                <p>
                    <?= $model->description; ?>
                </p>
            </article>

            <div class="sidebar-buttons-holder hide-desktop">
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
            <?php if ($model->relatedVideos ) : ?>
            <div class="widget-title medium">related videos</div>
            <ul class="post-list media-list">
                <?php foreach ($model->relatedVideos as $relatedVideo) : ?>
                <?php $video = $relatedVideo->video; ?>
                <li class="post-item media-item">
                    <?= Html::beginTag('a', [
                        'href' => Url::to(['/video/view', 'slug' => $video->url_key]),
                        'class' => 'img',
                        'style' => "background-image: url('".$video->getVideoImageLink()."')",
                    ]); ?>
                        <div class="icon-play"></div>
                    <?= Html::endTag('a'); ?>
                    <h2>
                        <?= Html::a($video->title, ['/video/view', 'slug' => $video->url_key]); ?>
                    </h2>
                    <h3><?= $video->description; ?></h3>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <!--
            <div class="widget-title medium">related articles</div>
            <ul class="post-list">
                <li class="post-item">
                    <ul class="article-rubrics-list">
                        <li><a href="/subject-areas/performance-of-migrants">Performance of migrants</a></li>
                        <li><a href="/subject-areas/migration-policy">Migration policy</a></li>
                    </ul>
                    <h2><a href="/articles/what-are-consequences-of-regularizing-undocumented-immigrants">What are the consequences of regularizing undocumented immigrants?</a></h2>
                    <h3>When countries regularize undocumented
                        residents, their work, wages, and human capital investment opportunities
                        change</h3>
                    <div class="publish"><a href="">Sherrie A. Kossoudji</a></div>
                    <div class="description">
                        Millions of people enter (or remain in)
                        countries without permission as they flee violence, war, or economic
                        hardship. Regularization policies that offer residence and work rights have
                        multiple and multi-layered effects on the economy and society, but they
                        always directly affect the labor market opportunities of those who are
                        regularized. Large numbers of undocumented people in many countries, a new
                        political willingness to fight for human and civil rights, and dramatically
                        increasing refugee flows mean continued pressure to enact regularization
                        policies.
                    </div>
                    <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                </li>
            </ul>
            <a class="btn-gray align-center show-more" href="">show more</a>
            -->
        </div>
        <aside class="sidebar-right">
            <div class="sidebar-buttons-holder hide-mobile">
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
                    <li class="sidebar-accrodion-item">
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
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">videos</a>
                        <div class="text">
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