<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<?php

$this->title = 'Opinions';
$this->params['breadcrumbs'][] = ['label' => Html::encode('Commentary'), 'url' => Url::to(['/event/index'])];
$this->params['breadcrumbs'][] = $this->title;

//if ($category) {
//    $this->registerMetaTag([
//    'name' => 'keywords',
//    'content' => Html::encode($category->meta_keywords)
//    ]);
//    $this->registerMetaTag([
//        'name' => 'title',
//        'content' => Html::encode($category->meta_title)
//    ]);
//}
?>


<?php
    $this->registerJsFile('/js/pages/opinions.js', ['depends' => ['yii\web\YiiAsset']]);
?>

<div class="container opinions-page">
    <div class="article-head-holder">
        <div class="article-head">
            <div class="breadcrumbs">
                <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
            </div>
            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li><a href="">Opinions</a></li>
                    <li><a href="">Videos</a></li>
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
            <h1>Opinions</h1>
            <div class="more-text-mobile">
                <p>
                    <?= Html::a('IZA World of Labor', ['/']); ?> articles provide concise, evidence-based analysis of policy-relevant topics in labor economics. We recognize that the articles will prompt discussion and possibly controversy. Opinion articles will capture these ideas and debates concisely, and anchor them with real-world examples. Opinions stated here do not necessarily reflect those of the IZA.</p>
                <a href="" class="more-evidence-map-text-mobile"><span class="more">More</span><span class="less">Less</span></a>
            </div>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text contact-page">
            <ul class="opinions-list">
                <?php foreach ($opinions as $opinion) : ?>
                <?php $hasImageClass = $opinion->image_link ? 'has-image' : null; ?>
                <li>
                    <div class="opinion-item <?= $hasImageClass; ?>">
                        <?php if ($hasImageClass) : ?>
                        <div class="img">
                            <?= Html::img('@app/web/uploads/opinions/'.$opinion->image_link, [
                                'alt' => 'Opinion image',
                            ]); ?>
                        </div>
                        <?php endif; ?>
                        <div class="desc">
                            <div class="inner">
                            <div class="date"><?= $opinion->created_at->format('F d, Y'); ?></div>
                            <div class="name"><a href="">Hardcoded Author</a></div>
                            <h2><a href=""><?= $opinion->title; ?> </a></h2>
                            <?php if ($opinion->short_description) : ?>
                            <p>
                                <?= $opinion->short_description; ?>
                            </p>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            
            </ul>
            <a class="btn-gray align-center" href="">show more</a>
        </div>

        <aside class="sidebar-right">
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
                </ul>
            </div>
            <div class="sidebar-widget sidebar-widget-subscribe">
                <div class="widget-title">stay up to date</div>
                <p>Register for our newsletter to receive regular updates on what we’re doing, latest news and forthcoming articles.</p>
                <a href="/subscribe" class="btn-blue">subscribe to newsletter</a>
            </div>
        </aside>
    </div>
</div>