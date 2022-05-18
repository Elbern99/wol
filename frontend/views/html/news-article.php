<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php

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
                    <li><a href="https://www.linkedin.com/showcase/iza-world-of-labor/" target="_blank"><span class="icon-linkedn"></span><span class="text">on linkedin</span></a></li>
                    <li><a href="http://www.facebook.com/pages/IZA-World-of-Labor/174866842714452" target="_blank"><span class="icon-facebook"></span><span class="text">on facebook</span></a></li>
                    <li><a href="https://plus.google.com/116017394173863766515" target="_blank"><span class="icon-google"></span><span class="text">on google+</span></a></li>
                </ul>
            </div>
            
		</aside>
	</div>
</div>