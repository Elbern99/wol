<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php

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
                        <!-- Sharingbutton Facebook -->
                        <a class="resp-sharing-button__link facebook-content" href="" target="_blank" aria-label="Facebook">
                            <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg></div>Facebook</div>
                        </a>
                    </li>
                    <li class="share-twitter">
                        <!-- Sharingbutton Twitter -->
                        <a class="resp-sharing-button__link twitter-content" href="" target="_blank" aria-label="Twitter">
                            <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg></div>Twitter</div>
                        </a>
                    </li>
                    <li class="share-ln">
                        <!-- Sharingbutton LinkedIn -->
                        <a class="resp-sharing-button__link linkedin-content" href="" target="_blank" aria-label="LinkedIn">
                            <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg></div>LinkedIn</div>
                        </a>
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
                        <!-- Sharingbutton Facebook -->
                        <a class="resp-sharing-button__link facebook-content" href="" target="_blank" aria-label="Facebook">
                            <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg></div>Facebook</div>
                        </a>
                    </li>
                    <li class="share-twitter">
                        <!-- Sharingbutton Twitter -->
                        <a class="resp-sharing-button__link twitter-content" href="" target="_blank" aria-label="Twitter">
                            <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg></div>Twitter</div>
                        </a>
                    </li>
                    <li class="share-ln">
                        <!-- Sharingbutton LinkedIn -->
                        <a class="resp-sharing-button__link linkedin-content" href="" target="_blank" aria-label="LinkedIn">
                            <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg></div>LinkedIn</div>
                        </a>
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