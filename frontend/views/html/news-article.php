<?php
/* @var $this yii\web\View */

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

<div class="container about-page">
	
	<div class="breadcrumbs">
		<ul class="breadcrumbs-list">
			<li><a href="">home</a></li>
			<li><a href="">news</a></li>
			<li>Air pollution has long-term effects on earnings as well as health</li>
		</ul>
	</div>
	
	<div class="content-inner">
		<div class="content-inner-text contact-page">
			<article class="full-article">
                <div class="head-news-holder">
                    <div class="head-news">
                        <div class="date">September 16, 2016</div>
                        <div class="publish"><a href="">Guardian, Financial Times</a></div>
                    </div>
                </div>
				<h1>Air pollution has long-term effects on earnings as well as health</h1>
				 
				<p>For every 10% increase in exposure to suspended particles in the year of their birth, a person’s earnings decrease by at least 1% once they reach 30 (and vice-versa), reveals a new study by researchers at the University of California, Santa Barbara and the US Treasury.<br><br>
				Using the passing of the Clean Air Act in the US in 1956, and its expanded scope in 1970, which imposed county-level restrictions on maximum amounts of suspended particles, researchers were able to study data on 5.7 million individuals born just before and just after the amendments to the act came into force, revealing the long-term effects of exposure to air pollution in children below the age of 1.<br><br>
				Although 1% may not sound particularly dramatic, cumulatively, that means $6.5 billion lower earnings per birth cohort studied.
				The researchers believe that two components could help explain the differences in earnings: health and cognitive ability. Children born in counties with higher air pollution had worse health, while previous studies have shown that exposure to air pollution in the womb and just after birth can result in reduced cognitive ability.<br><br>
				<a href="">Olivier Deschenes</a> has written about <a href="">environmental regulations and labor markets</a> for IZA World of Labor. He notes that whereas air quality standards generally have negative effects on industry employment, productivity, and worker earnings, the “private costs are small relative to the social benefits of better health outcomes for the population.” He recommends that any “new or stricter environmental regulations that affect labor markets should include job training, income support, and labor market reintegration programs” for displaced workers.<br><br>
				Read more <a href="">IZA World of Labor articles on the environment.</a></p>
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

            <div class="sidebar-widget">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">Latest news</a>
                        <div class="text">
                            <div class="text-inner">
                                <ul class="sidebar-news-list">
                                    <li><h3><a href="">New minimum wage will increase migration to UK, claim anti-EU campaigners</a></h3></li>
                                    <li><h3><a href="">New minimum wage will increase migration to UK, claim anti-EU campaigners</a></h3></li>
                                    <li><h3><a href="">New minimum wage will increase migration to UK, claim anti-EU campaigners</a></h3></li>
                                    <li><h3><a href="">New minimum wage will increase migration to UK, claim anti-EU campaigners</a></h3></li>
                                    <li><h3><a href="">New minimum wage will increase migration to UK, claim anti-EU campaigners</a></h3></li>
                                    <li><h3><a href="">New minimum wage will increase migration to UK, claim anti-EU campaigners</a></h3></li>
                                    <li><h3><a href="">New minimum wage will increase migration to UK, claim anti-EU campaigners</a></h3></li>
                                    <li><h3><a href="">New minimum wage will increase migration to UK, claim anti-EU campaigners</a></h3></li>
                                    <li><h3><a href="">New minimum wage will increase migration to UK, claim anti-EU campaigners</a></h3></li>
                                </ul>
                                <a href="" class="more-link">
                                    <span class="more">More</span>
                                    <span class="less">Less</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">news archives</a>
                        <div class="text">
                            <ul class="articles-filter-list date-list">
                                <li class="item open has-drop">
                                    <div class="icon-arrow"></div>
                                    <a href=""><strong>2016</strong></a>
                                    <ul class="submenu" style="display: block;">
                                        <li class="item"><a href="">September 2016</a></li>
                                        <li class="item"><a href="">July 2016</a></li>
                                        <li class="item"><a href="">June 2016</a></li>
                                        <li class="item"><a href="">May 2016</a></li>
                                        <li class="item"><a href="">March 2016</a></li>
                                    </ul>
                                </li>
                                <li class="item has-drop">
                                    <div class="icon-arrow"></div>
                                    <a href=""><strong>2015</strong></a>
                                    <ul class="submenu">
                                        <li class="item"><a href="">September 2016</a></li>
                                        <li class="item"><a href="">July 2016</a></li>
                                        <li class="item"><a href="">June 2016</a></li>
                                        <li class="item"><a href="">May 2016</a></li>
                                        <li class="item"><a href="">March 2016</a></li>
                                    </ul>
                                </li>
                                <li class="item has-drop">
                                    <div class="icon-arrow"></div>
                                    <a href=""><strong>2014</strong></a>
                                    <ul class="submenu">
                                        <li class="item"><a href="">September 2016</a></li>
                                        <li class="item"><a href="">July 2016</a></li>
                                        <li class="item"><a href="">June 2016</a></li>
                                        <li class="item"><a href="">May 2016</a></li>
                                        <li class="item"><a href="">March 2016</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">newsletters</a>
                        <div class="text">
                            <ul class="articles-filter-list date-list">
                                <li class="item open">
                                    <div class="icon-arrow"></div>
                                    <a href="/subject-areas/program-evaluation"><strong>2016</strong></a>
                                    <ul class="submenu">
                                        <li class="item">
                                            <div class="date">July 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                        <li class="item">
                                            <div class="date">June 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                        <li class="item">
                                            <div class="date">May 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                        <li class="item">
                                            <div class="date">April 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                        <li class="item">
                                            <div class="date">March 2016</div>
                                            <a href="">IZA WoL Newsletter</a>
                                        </li>
                                        <li class="item">
                                            <div class="date">February 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                        <li class="item">
                                            <div class="date">January 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="item">
                                    <div class="icon-arrow"></div>
                                    <a href="/subject-areas/program-evaluation"><strong>2015</strong></a>
                                    <ul class="submenu">
                                        <li class="item">
                                            <div class="date">July 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                        <li class="item">
                                            <div class="date">June 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="item">
                                    <div class="icon-arrow"></div>
                                    <a href="/subject-areas/program-evaluation"><strong>2014</strong></a>
                                    <ul class="submenu">
                                        <li class="item">
                                            <div class="date">July 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                        <li class="item">
                                            <div class="date">June 2016</div>
                                            <a href="">IZA World of Labor Newsletter</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">latest articles</a>
                        <div class="text">
                            <ul class="sidebar-news-list">
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
                                </li>
                                <li>
                                    <h3><a href="/articles/gender-diversity-in-teams">Gender diversity in teams</a></h3>
                                    <div class="writer">Ghazala Azmat</div>
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

			<div class="sidebar-widget sidebar-widget-subscribe">
				<div class="widget-title">stay up to date</div>
				<p>Register for our newsletter to receive regular updates on what we’re doing, latest news and forthcoming articles.</p>
				<a href="" class="btn-blue">subscribe to newsletter</a>
			</div>

            <div class="sidebar-widget">
                <div class="widget-title">follow iza world of labor</div>
                <ul class="socials-list socials-vertical-list">
                    <li><a href="http://twitter.com/IZAWorldofLabor" target="_blank"><span class="icon-twitter"></span><span class="text">on twitter</span></a></li>
                    <li><a href="http://www.linkedin.com/groups?gid=6610789&amp;mostPopular=&amp;trk=tyah&amp;trkInfo=tas%3AIZA%20wo%2Cidx%3A1-1-1" target="_blank"="><span class="icon-linkedn"></span><span class="text">on linkedin</span></a></li>
                    <li><a href="http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452" target="_blank"><span class="icon-facebook"></span><span class="text">on facebook</span></a></li>
                    <li><a href="https://plus.google.com/116017394173863766515" target="_blank"><span class="icon-google"></span><span class="text">on google+</span></a></li>
                </ul>
            </div>
            
		</aside>
	</div>
</div>