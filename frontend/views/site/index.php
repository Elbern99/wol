<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\articles\SubjectAreas;
use yii\widgets\Pjax;

$this->title = $page->Cms('meta_title');
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
                        <?php Pjax::begin(['linkSelector' => '#article_limit_button']); ?>
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
                                    <?php foreach($article['authors'] as $author): ?>
                                        <?= $author ?>
                                    <?php endforeach; ?>
                                    ,<?= date('F Y', $article['created_at']) ?></div>
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

                    <div class="news-list-holder">
                        <div class="widget-title medium"><a href="/news">latest news</a></div>
                        <ul class="post-list news-home-list">
                            <li>
                                <div class="post-item has-image">
                                    <a href="/news/news-01" class="img" style="background-image: url(/uploads/news/nnLC9fug1.jpg)"></a>
                                    <div class="desc">
                                        <div class="head-news-holder">
                                            <div class="head-news">
                                                <div class="date">January 05, 2017</div>
                                                <div class="publish">
                                                    <a href="#">rswderfwer</a>
                                                </div>
                                            </div>
                                        </div>
                                        <h2>
                                            <a href="/news/news-01">No evidence that women are less likely to ask for a pay rise, says study</a>
                                        </h2>
                                        <p class="hide-mobile">The gender pay gap is not due to women being less likely to ask for a raise, as is sometimes claimed, according to a new study. The study, published by the University of Warwick, found that women ask for a pay rise.</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="post-item has-image">
                                    <div class="desc">
                                        <div class="head-news-holder">
                                            <div class="head-news">
                                                <div class="date">September 9, 2016</div>
                                                <div class="publish">
                                                    <a href="#">BBC News</a>
                                                </div>
                                            </div>
                                        </div>
                                        <h2>
                                            <a href="/news/url">No evidence that women are less likely to ask for a pay rise, says study</a>
                                        </h2>
                                        <p class="hide-mobile">The gender pay gap is not due to women being less likely to ask for a raise, as is sometimes claimed, according to a new study. The study, published by the University of Warwick, found that women ask for a pay rise.</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="post-item has-image">
                                    <div class="desc">
                                        <div class="head-news-holder">
                                            <div class="head-news">
                                                <div class="date">September 7, 2016</div>
                                                <div class="publish">
                                                    <a href="#">Economic Policy Institute</a>
                                                </div>
                                            </div>
                                        </div>
                                        <h2>
                                            <a href="/news/url">Union decline creates wage stagnation, report finds</a>
                                        </h2>
                                        <p class="hide-mobile">The gender pay gap is not due to women being less likely to ask for a raise, as is sometimes claimed, according to a new study. The study, published by the University of Warwick, found that women ask for a pay rise.</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="post-item has-image">
                                    <div class="desc">
                                        <div class="head-news-holder">
                                            <div class="head-news">
                                                <div class="date">September 5, 2016</div>
                                                <div class="publish">
                                                    <a href="#">Guardian, BBC News</a>
                                                </div>
                                            </div>
                                        </div>
                                        <h2>
                                            <a href="/news/url">Brexit: May rejects points-based immigration</a>
                                        </h2>
                                        <p class="hide-mobile">UK prime minister Theresa May has ruled out adopting a points-based system for selecting migrants, as proposed by the Leave campaign in the run-up to the EU referendum. Speaking to journalists at the EU conference May claimed that such a system would not suit the UK.</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <a class="btn-gray align-center show-more" href="">show more</a>
                    </div>

                    <div class="other-commentary-list-holder">
                        <div class="widget-title medium"><a href="/commentary">commentary</a></div>
                        <ul class="post-list other-commentary-list">
                            <li class="post-item media-item">
                                <a href="" class="img" style="background-image: url(https://codepo8.github.io/canvas-images-and-pixels/img/horse.png)"></a>
                                <div class="category"><a href="/opinions">opinion</a></div>
                                <div class="author"><a href="">Daniel S. Hamermesh</a></div>
                                <h2><a href="">Does performance-related pay improve productivity?</a></h2>
                            </li>
                            <li class="post-item media-item">
                                <a href="" class="img" style="background-image: url(http://www.aee-community.com/wp-content/uploads/rtMedia/users/1/2016/09/2429637D00000578-0-image-a-284_1419003100839.jpg)">
                                    <div class="icon-play"></div>
                                </a>
                                <div class="category"><a href="/videos">video</a></div>
                                <h2><a href="">Dawn or Doom: The effects of Brexit on immigration, wages, and employment</a></h2>
                            </li>
                            <li class="post-item media-item">
                                <a href="" class="img" style="background-image: url(https://codepo8.github.io/canvas-images-and-pixels/img/horse.png)"></a>
                                <div class="category"><a href="/opinions">opinion</a></div>
                                <div class="author"><a href="">Daniel S. Hamermesh</a></div>
                                <h2><a href="">Does performance-related pay improve productivity?</a></h2>
                            </li>
                        </ul>
                        <a class="btn-gray align-center show-more" href="">show more</a>
                    </div>

                    <?php Pjax::begin(['linkSelector' => '#event_limit_button']); ?>
                    <div class="other-events-list-holder">
                        
                        <div class="widget-title medium"><a href="/events">events</a></div>
                        <ul class="post-list home-other-events-list media-list">
                            <?php foreach ($events as $event): ?>
                            <li class="post-item media-item">
                                <div class="date">July 21, 2016 - July 22, 2016</div>
                                <h2><a href=""><?= $event['title'] ?></a></h2>
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
                                <a href="/subject-areas/data" class="data-method-item gray">
                                    <div class="img"><img src="/images/temp/tranding-topics/01-img.jpg" alt=""></div>
                                    <div class="caption">
                                        <div class="icon-next-circle"></div>
                                        <h3>The impact of Brexit</h3>
                                    </div>
                                </a>
                                <a href="/subject-areas/methods" class="data-method-item gray">
                                    <div class="img"><img src="/images/temp/tranding-topics/02-img.jpg" alt=""></div>
                                    <div class="caption">
                                        <div class="icon-next-circle"></div>
                                        <h3>The Chinese economy</h3>
                                    </div>
                                </a>
                                <a href="/subject-areas/methods" class="data-method-item gray">
                                    <div class="img"><img src="/images/temp/tranding-topics/03-img.jpg" alt=""></div>
                                    <div class="caption">
                                        <div class="icon-next-circle"></div>
                                        <h3>Transitional markets in South America</h3>
                                    </div>
                                </a>
                                <a href="/subject-areas/methods" class="data-method-item gray">
                                    <div class="img"><img src="/images/temp/tranding-topics/04-img.jpg" alt=""></div>
                                    <div class="caption">
                                        <div class="icon-next-circle"></div>
                                        <h3>Immigration</h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-widget sidebar-widget-subscribe">
                        <div class="widget-title">iza world of labor on twitter</div>
                        <a class="twitter-timeline custom-tr" data-lang="en" data-dnt="true" data-chrome="noheader transparent nofooter" data-tweet-limit="5" data-cards="hidden" data-theme="light" data-link-color="#0053a0" href="https://twitter.com/IZAWorldofLabor">Tweets by IZAWorldofLabor</a>
                        <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
                        <a href="https://twitter.com/IZAWorldofLabor" class="twitter-follow-button" data-size="large" data-show-count="false">Follow @IZAWorldofLabor</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>

<?= $page->getWidgetByName('sticky_newsletter') ?>
