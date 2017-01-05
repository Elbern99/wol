<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php

$this->registerJsFile('/js/plugins/share-buttons.js', ['depends' => ['yii\web\YiiAsset']]);

$newsItemDirectLink = Url::to(['/opinion/view', 'slug' => $model->url_key], true);
$mailLink = $newsItemDirectLink;
$mailTitle = $model->title;
$mailBody = 'Hi.\n\n I think that you would be interested in the  following article from IZA World of labor. \n\n  Title: '. $mailTitle .
    '\n\n View the article: '. Html::a($mailLink, $mailLink) . '\n\n Copyright © IZA 2016'.'Impressum. All Rights Reserved. ISSN: 2054-9571';

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
$this->params['breadcrumbs'][] = ['label' => Html::encode('News'), 'url' => Url::to(['/news/index'])];
$this->params['breadcrumbs'][] = $model->title;

?>

<div class="container about-page">
	
	<div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
	</div>
        <div class="mobile-filter-holder custom-tabs-holder">
            <ul class="mobile-filter-list">
                <li><a href="" class="js-widget">Latest news</a></li>
                <li><a href="" class="js-widget">News archives</a></li>
                <li><a href="" class="js-widget">Newsletters</a></li>
            </ul>
            <div class="mobile-filter-items custom-tabs">
                <div class="tab-item js-tab-hidden empty">
                     <ul class="sidebar-news-list">
                        <?php foreach ($newsSidebar as $item) : ?>
                        <li>
                            <h3>
                                <?= Html::a($item->title, ['/news/view', 'slug' => $item->url_key]); ?>
                            </h3>
                            <div class="writer"><?= $item->editor; ?></div>
                        </li>
                        <?php endforeach; ?>
                        
                    </ul>
                    <?php if (count($newsSidebar) > Yii::$app->params['latest_news_sidebar_limit']): ?>
                    <a href="" class="more-link">
                        <span class="more">More</span>
                        <span class="less">Less</span>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="tab-item blue js-tab-hidden expand-more">
                    <ul class="articles-filter-list date-list blue-list">
                        <?php foreach ($newsTree as $key => $value) : ?>
                        <li class="item">
                            <div class="icon-arrow"></div>
                            <?= Html::a($key, ['news/index', 'year' => $key]) ?>
                            <ul class="submenu">
                                <?php foreach ($value['months'] as $month): ?>
                                <li class="item">
                                    <?php $monthYear = date("F", mktime(0, 0, 0, $month['num'], 10)) . ' ' . $key; ?>
                                    <?= Html::a($monthYear, ['news/index', 'year' => $key, 'month' => $month['num']]) ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="tab-item blue js-tab-hidden expand-more">
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
	
	<div class="content-inner">
            <div class="content-inner-text contact-page">
                <article class="full-article">
                    <div class="head-news-holder">
                        <div class="head-news">
                            <div class="date">
                                <?= $model->created_at->format('F d, Y'); ?>
                            </div>
                            <div class="publish">
                                <a href="#"><?= $model->editor; ?></a>
                            </div>
                        </div>
                    </div>
                    <h1><?php $model->title; ?></h1>
                    <?php $hasImage= $model->image_link ? true : false; ?>
                    <?php if ($hasImage) : ?>
                    <figure class="align-left">
                        <?= Html::img('@web/uploads/news/'.$model->image_link, [
                            'alt' => $model->title,
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

            <div class="sidebar-widget">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">Latest news</a>
                        <div class="text">
                            <div class="text-inner">
                                <ul class="sidebar-news-list">
                                    <?php foreach ($newsSidebar as $item) : ?>
                                    <li>
                                        <h3>
                                            <?= Html::a($item->title, ['/news/view', 'slug' => $item->url_key]); ?>
                                        </h3>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if (count($newsSidebar) > Yii::$app->params['latest_news_sidebar_limit']): ?>
                                <a href="" class="more-link">
                                    <span class="more">More</span>
                                    <span class="less">Less</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">news archives</a>
                        <div class="text">
                            <ul class="articles-filter-list date-list">
                                <?php foreach ($newsTree as $key => $value) : ?>
                                <li class="item has-drop <?php if($value['isActive']) echo 'open'; ?>">
                                    <div class="icon-arrow"></div>
                                    <strong><?= Html::a($key, ['news/index', 'year' => $key]) ?></strong>
                                    <ul class="submenu">
                                        
                                        <?php foreach ($value['months'] as $month): ?>
                                            <li class="item <?php if($month['isActive']) echo 'open'; ?>">
                                                <?php $monthYear = date("F", mktime(0, 0, 0, $month['num'], 10)) . ' ' . $key; ?>
                                                <?= Html::a($monthYear, ['news/index', 'year' => $key, 'month' => $month['num']]) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <?php endforeach; ?>
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
                                <?php foreach($articlesSidebar as $article) : ?>
                                <li>
                                    <h3>
                                        <?= Html::a($article->title, ['/article/one-pager', 'slug' => $article->seo]); ?>
                                    </h3>
                                    <div class="writer"><?= $article->availability; ?></div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (count($articlesSidebar) > Yii::$app->params['latest_articles_sidebar_limit']): ?>
                            <a href="" class="more-link">
                                <span class="more">More</span>
                                <span class="less">Less</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>

            <?php if (count($widgets)): ?>
            <div class="sidebar-widget">
                <div class="podcast-list">
                    <?php foreach ($widgets as $widget): ?>
                        <?= $widget['text'] ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            </aside>
	</div>
</div>