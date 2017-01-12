<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<?php

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;

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
?>
<div class="container news-list-page">
    <div class="article-head-holder">
        <div class="article-head">
            <div class="breadcrumbs">
                <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
            </div>
            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li class="active"><a href="" class="js-widget">Latest news</a></li>
                    <li><a href="" class="js-widget">News archives</a></li>
                    <li><a href="" class="js-widget">Newsletters</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item active empty">
                        <div class="clone-title">News from IZA World of Labor</div>
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
            <h1 class="hide-mobile">News from IZA World of Labor</h1>
            <p class="hide-mobile">We draw on a range of global sources to bring you news related to IZA World of Labor articles.</p>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <div class="post-list-clone-holder">
                <div class="post-list-clone">
                    <?php Pjax::begin(['linkSelector' => '.btn-gray', 'enableReplaceState' => false, 'enablePushState' => false]); ?>
                    <ul class="post-list clone news-list">
                        <?php foreach ($news as $item) : ?>
                        <?php $hasImageClass = $item->image_link ? 'has-image' : null; ?>
                        <li>
                            <div class="post-item <?= $hasImageClass; ?>">
                                <?php if ($hasImageClass) : ?>
                                <a href="/news/<?= $item->url_key; ?>" class="img" style="background-image: url(<?= '/uploads/news/'.$item->image_link; ?>)"></a>
                                <?php endif; ?>
                                <div class="desc">
                                    <div class="inner">
                                        <div class="head-news-holder">
                                            <div class="head-news">
                                                <div class="date">
                                                    <?= $item->created_at->format('F d, Y'); ?>
                                                </div>
                                                <div class="publish">
                                                    <a href="#">
                                                        <?= $item->editor; ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <h2>
                                            <a href="#">
                                                <?= Html::a($item->title, ['/news/view', 'slug' => $item->url_key]); ?>
                                            </a>
                                        </h2>
                                        <p class="hide-mobile"><?= $item->short_description; ?></p>
                                    </div>
                                </div>
                                <p class="hide-desktop"><?= $item->short_description; ?></p>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if ($newsCount > $limit): ?>
                    <?php $params = [ 'limit' => $limit]; ?>
                    <?= Html::a("show more", Url::current($params), ['class' => 'btn-gray align-center']) ?>
                    <?php else: ?>
                        <?php if (Yii::$app->request->get('limit')): ?>
                            <?= Html::a("clear", Url::current(), ['class' => 'btn-gray align-center']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php Pjax::end(); ?>
                </div>
                
            </div>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item hide-mobile is-open">
                        <a href="" class="title">news archives</a>
                        <div class="text">
                            <ul class="articles-filter-list date-list ">
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
                    <li class="sidebar-accrodion-item hide-mobile">
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
                <?php foreach ($widgets as $widget): ?>
                    <?= $widget['text'] ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </aside>
    </div>
</div>