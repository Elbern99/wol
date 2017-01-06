<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<?php
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