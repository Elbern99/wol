<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\articles\SubjectAreas;
?>

<?php

$this->title = 'Articles';
$this->params['breadcrumbs'][] = $this->title;

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($category->meta_keywords)
]);
$this->registerMetaTag([
    'name' => 'title',
    'content' => Html::encode($category->meta_title)
]);

?>

<div class="container">
    <div class="breadcrumbs">
        <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
    </div>

    <h1><?= $category->meta_title ?></h1>
    <p><?= $category->description ?></p>
    <div class="content-inner">
        
        <div class="content-inner-text">
            <div class="articles">
                <ul class="articles-list">
                    <?php foreach($collection as $article): ?>
                    <li class="article-item">
                        <ul class="article-rubrics-list">
                            <?php foreach($article['category'] as $link): ?>
                                <li><?= $link ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <h2><a href="<?= $article['url'] ?>"><?= $article['title'] ?></a></h2>
                        <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
                        <div class="publish"><a href=""><?= $article['availability']  ?></a>, <?= date('F Y', $article['created_at']) ?></div>
                        <div class="description">
                            <?= $article['abstract']->abstract ?? ''; ?>
                        </div>
                        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a href="" class="btn-gray align-center">show more</a>
            </div>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-sort-by">
                <label>sort by</label>
                <label class="custom-select">
                    <div name="sort">
                        <span value="date-desc">
                            <a href="<?= Url::to('/articles') ?>">Publication date (descending)</a>
                        </span>
                        <span value="date-asc" <?= ($sort) ? 'selected="selected"' : '' ?>>
                            <a href="<?= Url::to(['/articles', 'sort' => 1]) ?>">Publication date (ascending)</a>
                        </span>
                    </div>
                </label>
            </div>
            <div class="sidebar-widget">
                <div class="sidebar-widget sidebar-widget-articles-filter">
                    <ul class="sidebar-accrodion-list">
                        <li class="sidebar-accrodion-item is-open">
                            <a href="" class="title">subject areas</a>
                            <div class="text is-open">
                                <div class="text-inner">
                                    <?= SubjectAreas::widget(['category' => $subjectAreas]) ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="sidebar-widget">
               <div class="widget-title">data & methods</div>
                <div class="data-method-list">
                    <a href="/subject-areas/data" class="data-method-item">
                        <div class="img"><img src="images/temp/editors/img-01.jpg" alt=""></div>
                        <div class="caption">
                            <div class="icon-circle-arrow white">
                                <div class="icon-arrow"></div>
                            </div>
                            <h3>View all of our data sources in one place</h3>
                        </div>
                    </a>
                    <a href="/subject-areas/methods" class="data-method-item">
                        <div class="img"><img src="images/temp/article/img-02.jpg" alt="" width="430" height="326"></div>
                        <div class="caption">
                            <div class="icon-circle-arrow white">
                                <div class="icon-arrow"></div>
                            </div>
                            <h3>Explore our methods</h3>
                        </div>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>