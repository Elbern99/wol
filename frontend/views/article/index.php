<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\articles\SubjectAreas;
use yii\widgets\Pjax;
?>

<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'Articles';
$this->params['breadcrumbs'][] = 'Articles';

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($category->meta_keywords)
]);
$this->registerMetaTag([
    'name' => 'title',
    'content' => Html::encode($category->meta_title)
]);

$currentUrl[] = '/articles';
$currentParams = Yii::$app->getRequest()->getQueryParams();
unset($currentParams['id']);
$currentUrl = array_merge($currentUrl, $currentParams);
unset($currentParams);
?>

<div class="container">

    <div class="articles-head">
        <div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
        </div>
        <div class="mobile-filter-holder custom-tabs-holder">
            <ul class="mobile-filter-list">
                <li><a href="" class="js-widget">Subject areas</a></li>
                <li><a href="" class="js-widget">Authors</a></li>
            </ul>
            <div class="mobile-filter-items custom-tabs">
                <div class="tab-item blue js-tab-hidden expand-more tab-item-subject-areas">
                    <?= SubjectAreas::widget(['category' => $subjectAreas]) ?>
                </div>
                <div class="tab-item blue js-tab-hidden expand-more tab-item-subject-areas">
                    <?= \frontend\components\widget\AuthorLetterWidget::widget([]); ?>
                </div>
            </div>
        </div>
        <h1><?= $category->meta_title ?></h1>
        <div class="desc-category">
            <p><?= $category->description ?></p>
        </div>
    </div>
    
    <div class="content-inner">
        <?php Pjax::begin(['linkSelector' => '.btn-gray', 'options' => ['class' => 'loader-ajax']]); ?>
        <div class="content-inner-text">
            <div class="articles">
                <div class="sidebar-widget sidebar-widget-sort-by hide-desktop">
                    <?= $this->renderFile('@frontend/views/article/order.php', ['currentUrl' => $currentUrl]); ?>
                </div>
                <ul class="articles-list">
                    <?php foreach($collection as $article): ?>
                    <li class="article-item">
                        <ul class="article-rubrics-list">
                            <?php foreach($article['category'] as $link): ?>
                                <li><?= $link ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <h2>
                            <a href="<?= $article['url'] ?>"><?= $article['title'] ?></a>
                            <?php if ($article['isShowLabel']) : ?>
                                <span class="version-label">Updated</span>
                            <?php endif; ?>
                        </h2>
                        <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
                        <div class="writers">
                            <?php foreach($article['authors'] as $author): ?>
                            <span class="writer-item"><?= $author ?></span><?php endforeach; ?>,
                            <?= date('F Y', $article['created_at']) ?>
                        </div>
                        <div class="description">
                            <?= $article['abstract']->abstract ?? ''; ?>
                        </div>
                        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php if ($articleCount > $limit): ?>
                    <?= Html::a("show more", Url::to(array_merge($currentUrl, ['limit' => $limit])), ['class' => 'btn-gray align-center','update']) ?>
                <?php else: ?>
                    <?php if (Yii::$app->request->get('limit')): ?>
                        <?php if (Yii::$app->request->get('limit')): ?>
                            <?= Html::a("clear", Url::to(array_merge($currentUrl, ['limit' => 0])), ['class' => 'btn-gray align-center']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php Pjax::end(); ?>
        
        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-sort-by hide-mobile">
                <?= $this->renderFile('@frontend/views/article/order.php', ['currentUrl' => $currentUrl]); ?>
            </div>
            <div class="sidebar-widget sidebar-widget-articles-filter hide-mobile">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">subject areas</a>
                        <div class="text is-open">
                             <?= SubjectAreas::widget(['category' => $subjectAreas]) ?>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Authors</a>
                        <div class="text is-open">
                            <?= \frontend\components\widget\AuthorLetterWidget::widget([]); ?>
                        </div>
                    </li>
                </ul>
            </div>

            <?= $widgets->getPageWidget('data_methods') ?>
        </aside>
    </div>
</div>

<div class="back-to-top"><div class="icon-upwards-arrow"></div></div>