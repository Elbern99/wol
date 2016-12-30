<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
    $this->registerJsFile('/js/plugins/share-buttons.js', ['depends' => ['yii\web\YiiAsset']]);

$mailLink = 'link';
$mailTitle = 'title';
$mailBody = 'Hi.\n\n I think that you would be interested in the  following article from IZA World of labor. \n\n  Title: '. $mailTitle .
    '\n\n View the video: '. $mailLink . '\n\n Copyright © IZA 2016'.'Impressum. All Rights Reserved. ISSN: 2054-9571';
?>

<div class="container single-post-page">
    <div class="article-head-holder">
        <div class="article-head">
            <div class="breadcrumbs">
                <ul class="breadcrumbs-list"><li><a href="/">Home</a></li>
                    <li><a href="/articles">articles</a></li>
                </ul>
            </div>

            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li><a href="" class="js-widget">Opinions</a></li>
                    <li><a href="" class="js-widget">Videos</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item js-tab-hidden expand-more">
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
                    <div class="tab-item js-tab-hidden expand-more">
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
                </div>
            </div>
            <h1>Dawn or Doom: The effects of Brexit on immigration, wages, and employment</h1>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text contact-page">
            <article class="post-full-item">
                <figure>
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/Z90m9yKaT1M" frameborder="0" allowfullscreen></iframe>
                </figure>
                <p>A panel discussion with economist Jonathan Portes, Conservative politician Geoffrey Van Orden MEP, Professor L. Alan Winters and moderated by Economist journalist Philip Coggan.</p>
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

            <div class="widget-title medium">related videos</div>
            <ul class="post-list media-list">
                <li class="post-item media-item">
                    <a href="" class="img" style="background-image: url(https://codepo8.github.io/canvas-images-and-pixels/img/horse.png)">
                        <div class="icon-play"></div>
                    </a>
                    <h2><a href="/articles/what-are-consequences-of-regularizing-undocumented-immigrants">What are the consequences of regularizing undocumented immigrants?</a></h2>
                    <h3>When countries regularize undocumented residents, their work, wages, and human capital investment opportunities change</h3>
                </li>
                <li class="post-item media-item">
                    <a href="" class="img" style="background-image: url(http://www.aee-community.com/wp-content/uploads/rtMedia/users/1/2016/09/2429637D00000578-0-image-a-284_1419003100839.jpg)">
                        <div class="icon-play"></div>
                    </a>
                    <h2><a href="/articles/what-are-consequences-of-regularizing-undocumented-immigrants">What are the consequences of regularizing undocumented immigrants?</a></h2>
                    <h3>When countries regularize undocumented residents, their work, wages, and human capital investment opportunities change</h3>
                </li>
                <li class="post-item media-item">
                    <a href="" class="img" style="background-image: url(http://image.shutterstock.com/z/stock-photo-image-of-male-entrepreneur-walking-on-the-road-with-numbers-while-carrying-suitcase-336606005.jpg)">
                        <div class="icon-play"></div>
                    </a>
                    <h2><a href="/articles/what-are-consequences-of-regularizing-undocumented-immigrants">What are the consequences of regularizing undocumented immigrants?</a></h2>
                    <h3>When countries regularize undocumented residents, their work, wages, and human capital investment opportunities change</h3>
                </li>
                <li class="post-item media-item">
                    <a href="" class="img" style="background-image: url(https://s4.scoopwhoop.com/anj/news/577034596.jpg)">
                        <div class="icon-play"></div>
                    </a>
                    <h2><a href="/articles/what-are-consequences-of-regularizing-undocumented-immigrants">What are the consequences of regularizing undocumented immigrants?</a></h2>
                    <h3>When countries regularize undocumented residents, their work, wages, and human capital investment opportunities change</h3>
                </li>
                <li class="post-item media-item">
                    <a href="" class="img" style="background-image: url(https://i.kinja-img.com/gawker-media/image/upload/s--g08OhiOf--/c_scale,fl_progressive,q_80,w_800/pide90n0psa6euojt1uw.jpg)">
                        <div class="icon-play"></div>
                    </a>
                    <h2><a href="/articles/what-are-consequences-of-regularizing-undocumented-immigrants">What are the consequences of regularizing undocumented immigrants?</a></h2>
                    <h3>When countries regularize undocumented residents, their work, wages, and human capital investment opportunities change</h3>
                </li>
                <li class="post-item media-item">
                    <a href="" class="img" style="background-image: url(https://en-support.files.wordpress.com/2009/09/image-widget-wordpress.png?w=380&h=600)">
                        <div class="icon-play"></div>
                    </a>
                    <h2><a href="/articles/what-are-consequences-of-regularizing-undocumented-immigrants">What are the consequences of regularizing undocumented immigrants?</a></h2>
                    <h3>When countries regularize undocumented residents, their work, wages, and human capital investment opportunities change</h3>
                </li>
            </ul>
            <a class="btn-gray align-center show-more" href="">show more</a>
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
                <li class="post-item">
                    <ul class="article-rubrics-list">
                        <li><a href="/subject-areas/performance-of-migrants">Performance of migrants</a></li>
                        <li><a href="/subject-areas/migration-policy">Migration policy</a></li>
                    </ul>
                    <h2><a href="/articles/what-are-consequences-of-regularizing-undocumented-immigrants">What are the consequences of regularizing undocumented immigrants?</a></h2>
                    <h3>When countries regularize undocumented
                        residents, their work, wages, and human capital investment opportunities
                        change</h3>
                    <div class="publish"><a href="">Sherrie A. Kossoudji</a>, September 2016</div>
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