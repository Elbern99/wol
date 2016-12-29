<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<?php
    $this->registerJsFile('/js/plugins/share-buttons.js', ['depends' => ['yii\web\YiiAsset']]);

$mailLink = 'link';
$mailTitle = 'title';
$mailBody = 'Hi.\n\n I think that you would be interested in the  following article from IZA World of labor. \n\n  Title: '. $mailTitle .
    '\n\n View the article: '. $mailLink . '\n\n Copyright © IZA 2016'.'Impressum. All Rights Reserved. ISSN: 2054-9571';
?>

<div class="container media-page">
    <div class="article-head-holder">
        <div class="article-head">
            <div class="breadcrumbs">
                <ul class="breadcrumbs-list">
                    <li><a href="/">Home</a></li>
                    <li><a href="">articles</a></li>
                </ul>
            </div>
            <div class="mobile-filter-holder">
                <ul class="mobile-filter-list">
                    <li class="active"><a href="/opinions">Opinions</a></li>
                    <li><a href="/videos">Videos</a></li>
                </ul>
            </div>
            <h1>Dawn or Doom: The effects of Brexit on immigration, wages, and employment</h1>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text contact-page">
            <article class="media-post">
                <figure>
                    <img src="http://www.w3schools.com/css/img_fjords.jpg" alt="">
                </figure>
                <p>A panel discussion with economist Jonathan Portes, Conservative politician Geoffrey Van Orden MEP, Professor L. Alan Winters and moderated by Economist journalist Philip Coggan.</p>
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
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                    <div class="writer">Augustin De Coulon</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                    <div class="writer">Roman M. Sheremeta</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                    <div class="writer">Augustin De Coulon</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                    <div class="writer">Roman M. Sheremeta</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                    <div class="writer">Augustin De Coulon</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                    <div class="writer">Roman M. Sheremeta</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                    <div class="writer">Augustin De Coulon</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                    <div class="writer">Roman M. Sheremeta</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                    <div class="writer">Augustin De Coulon</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                    <div class="writer">Roman M. Sheremeta</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                    <div class="writer">Augustin De Coulon</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                    <div class="writer">Roman M. Sheremeta</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                    <div class="writer">Augustin De Coulon</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                    <div class="writer">Roman M. Sheremeta</div>
                                </li>
                            </ul>
                            <a href="" class="more-link">
                                <span class="more">More</span>
                                <span class="less">Less</span>
                            </a>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">videos</a>
                        <div class="text is-open">
                            <ul class="sidebar-news-list">
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/pros-and-cons-of-workplace-tournaments">The pros and cons of workplace tournaments</a></h3>
                                </li>
                                <li>
                                    <h3><a href="/articles/where-do-immigrants-retire-to">Where do immigrants retire to?</a></h3>
                                </li>
                            </ul>
                            <a href="" class="more-link">
                                <span class="more">More</span>
                                <span class="less">Less</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="sidebar-widget">
                <div class="podcast-list">
                    <a href="" class="podcast-item">
                        <div class="head">
                            <div class="widget-title">podcast: brexit debate</div>
                            <div class="img">
                                <img src="/images/temp/podcasts/01-img.jpg" alt="">
                            </div>
                        </div>
                    </a>
                    <a href="" class="podcast-item">
                        <div class="head">
                            <div class="widget-title">Focus on: Education and labor policy</div>
                            <div class="img">
                                <img src="/images/temp/podcasts/02-img.jpg" alt="">
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>