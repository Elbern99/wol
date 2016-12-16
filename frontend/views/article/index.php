<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\articles\SubjectAreas;
use yii\widgets\Pjax;
//use Yii;
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

    <div class="articles-head">
        <div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
        </div>
        <h1><?= $category->meta_title ?></h1>
        <p><?= $category->description ?></p>
    </div>
    
    <div class="content-inner">
        <?php Pjax::begin(['linkSelector' => '.btn-gray']); ?>
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
                <?php if ($articleCount > $limit): ?>
                    <?php
                    if ($sort == 3) {
                        $params = ['/articles', 'limit' => $limit];
                    } else {
                        $params = ['/articles', 'limit' => $limit, 'sort' => 1];
                    }
                    ?>
                    <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray align-center']) ?>
                <?php else: ?>
                    <?php if (Yii::$app->request->get('limit')): ?>
                        <?php
                        if ($sort == 3) {
                            $params = ['/articles'];
                        } else {
                            $params = ['/articles', 'sort' => 1];
                        }
                        ?>
                        <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray align-center']) ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php Pjax::end(); ?>
        
        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-sort-by">
                <label>sort by</label>
                <div class="custom-select dropdown">
                    <div class="custom-select-title dropdown-link">
                        Publication date (descending)
                    </div>
                    <div class="sort-list drop-content">
                        <div>
                            <a href="<?= Url::to('/articles') ?>">Publication date (descending)</a>
                        </div>
                        <div <?= ($sort != 3) ? 'data-select="selected"' : '' ?>>
                            <a href="<?= Url::to(['/articles', 'sort' => 1]) ?>">Publication date (ascending)</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-widget">
               <div class="widget-title">data & methods</div>
                <div class="data-method-list">
                    <a href="/subject-areas/data" class="data-method-item">
                        <div class="img"><img src="/images/temp/articles/01-img.jpg" alt=""></div>
                        <div class="caption">
                            <div class="icon-next-circle"></div>
                            <h3>View all of our data sources in one place</h3>
                        </div>
                    </a>
                    <a href="/subject-areas/methods" class="data-method-item">
                        <div class="img"><img src="/images/temp/articles/02-img.jpg" alt="" width="430" height="326"></div>
                        <div class="caption">
                            <div class="icon-next-circle"></div>
                            <h3>Explore our methods</h3>
                        </div>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>