<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>
<?php

$this->title = 'Opinions';
$this->params['breadcrumbs'][] = ['label' => Html::encode('Commentary'), 'url' => Url::to(['/event/index'])];
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
                    <li><a href="" class="js-widget">Opinions</a></li>
                    <li><a href="" class="js-widget">Videos</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item js-tab-hidden expand-more">
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
                    <div class="tab-item js-tab-hidden expand-more">
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
        <?php Pjax::begin(['linkSelector' => '.btn-gray']); ?>
        <div class="content-inner-text contact-page">
            <ul class="opinions-list">
                <?php foreach ($opinions as $opinion) : ?>
                <?php $hasImageClass = $opinion->image_link ? 'has-image' : null; ?>
                <li>
                    <div class="opinion-item <?= $hasImageClass; ?>">
                        <?php if ($hasImageClass) : ?>
                        <a href="<?= '/opinions/'. $opinion->url_key; ?>" title="<?= $opinion->title ?>" class="img" style="background-image: url(<?= '/uploads/opinions/'.$opinion->image_link; ?>)"></a>
                        <?php endif; ?>
                        <div class="desc">
                            <div class="inner">
                                <div class="date"><?= $opinion->created_at->format('F d, Y'); ?></div>
                                <div class="name"><a href="">Hardcoded Author</a></div>
                                <h2><?= Html::a($opinion->title, ['/opinion/view', 'slug' => $opinion->url_key]); ?></h2>
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
            <?php if ($opinionsCount > $limit): ?>
                    <?php $params = ['/opinion/index', 'limit' => $limit]; ?>
                    <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray align-center']) ?>
            <?php else: ?>
                <?php if (Yii::$app->request->get('limit')): ?>
                     <?php $params = ['/opinion/index']; ?>
                    <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray align-center']) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php Pjax::end(); ?>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
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
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">videos</a>
                        <div class="text is-open">
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

            <div class="sidebar-widget sidebar-widget-subscribe">
                <?php foreach ($widgets as $widget): ?>
                   <?= $widget['text'] ?>
                <?php endforeach; ?>
            </div>
        </aside>
    </div>
</div>