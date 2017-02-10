<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\articles\SubjectAreas;
use yii\widgets\Pjax;
use common\modules\author\Roles;
?>

<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.$category->title;
$this->params['breadcrumbs'][] = ['label'=>'Articles', 'url'=>Url::to('articles', true)];
if ($parentCategory) {
    $this->params['breadcrumbs'][] = ['label'=>$parentCategory->title, 'url'=>Url::to($parentCategory->url_key)];
}
$this->params['breadcrumbs'][] = $category->title;

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($category->meta_keywords)
]);
$this->registerMetaTag([
    'name' => 'title',
    'content' => Html::encode($category->meta_title)
]);

$roleLabel = new Roles();
$currentUrl[] = $category->url_key;
$currentParams = Yii::$app->getRequest()->getQueryParams();
unset($currentParams['id']);
$currentUrl = array_merge($currentUrl, $currentParams);
unset($currentParams);
?>

<div class="container">

    <div class="article-head">
        <div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
        </div>
        <div class="mobile-filter-holder custom-tabs-holder">
            <ul class="mobile-filter-list">
                <li><a href="" class="js-widget">Subject areas</a></li>
                <li><a href="" class="js-widget">Authors</a></li>
            </ul>
            <div class="mobile-filter-items custom-tabs">
                <div class="tab-item blue js-tab-hidden expand-more">
                    <?= SubjectAreas::widget(['category' => $subjectAreas]) ?>
                </div>
                <div class="tab-item blue js-tab-hidden expand-more">
                    test 3
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
                    <label>sort by</label>
                    <div class="custom-select dropdown">
                        <div class="custom-select-title dropdown-link">
                            Publication date (descending)
                        </div>
                        <div class="sort-list drop-content">
                            <div>
                                <a href="<?= Url::to(array_merge($currentUrl, ['sort' => 0])) ?>">Publication date (descending)</a>
                            </div>
                            <div <?= ($sort != 3) ? 'data-select="selected"' : '' ?>>
                                <a href="<?= Url::to(array_merge($currentUrl, ['sort' => 1])) ?>">Publication date (ascending)</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (count($editors['subject']) || count($editors['associate'])): ?>
                <div class="article-user-list-holder">
                    <div class="mobile-accordion-item dropdown">
                        <div class="title mobile-accordion-link">
                            editorial team
                        </div>
                        <div class="mobile-accordion-drop drop-content">
                            <?php if (count($editors['subject'])): ?>
                            <h3><?= Yii::t('app/text', 'Subject Editor') ?></h3>
                            <div class="article-user-list">
                                <?php foreach ($editors['subject'] as $editor): ?>
                                <div class="article-user">
                                    <div class="img-holder img-holder-bg">
                                        <a href="<?= $editor['profile'] ?>" class="img" style="background-image: url(<?= $editor['avatar'] ?>)"></a>
                                    </div>
                                    <div class="desc">
                                        <div class="name">
                                            <a href="<?= $editor['profile'] ?>"><?= $editor['name'] ?></a>
                                        </div>
                                        <p><?= $editor['affiliation'] ?? '' ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <?php if (count($editors['associate'])): ?>
                            <h3><?= Yii::t('app/text', 'Associate Editor(s)') ?></h3>
                            <div class="article-user-list">
                                <?php foreach ($editors['associate'] as $editor): ?>
                                <div class="article-user">
                                    <div class="img-holder img-holder-bg">
                                        <a href="<?= $editor['profile'] ?>" class="img" style="background-image: url(<?= $editor['avatar'] ?>)"></a>
                                    </div>
                                    <div class="desc">
                                        <div class="name">
                                            <a href="<?= $editor['profile'] ?>"><?= $editor['name'] ?></a>
                                        </div>
                                        <p><?= $editor['affiliation'] ?? '' ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

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
                        <div class="publish"><?php foreach($article['authors'] as $author): ?><?= $author ?><?php endforeach; ?>, <?= date('F Y', $article['created_at']) ?>
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
                <label>sort by</label>
                <div class="custom-select dropdown">
                    <div class="custom-select-title dropdown-link">
                        Publication date (descending)
                    </div>
                    <div class="sort-list drop-content">
                        <div>
                            <a href="<?= Url::to(array_merge($currentUrl, ['sort' => 0])) ?>">Publication date (descending)</a>
                        </div>
                        <div <?= ($sort != 3) ? 'data-select="selected"' : '' ?>>
                            <a href="<?= Url::to(array_merge($currentUrl, ['sort' => 1])) ?>">Publication date (ascending)</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">subject areas</a>
                        <div class="text is-open">
                            <div class="text-inner">
                                <?= SubjectAreas::widget(['category' => $subjectAreas, 'currentId' => $category->id]) ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div>
                <div>Authors</divl>
                <div class="text">
                    <?php $alphas = range('A', 'Z'); ?>
                    <ul class="abs-list">
                        <?php foreach ($alphas as $letter): ?>
                            <li><a class="profile-author-letter" href="<?= Url::to(['/authors', 'filter' => $letter]) ?>"><span class="text"><?= $letter ?></span></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div>
                <div>Background information</divl>
                <div class="text">
                    <?php $alphas = range('A', 'Z'); ?>
                    <ul class="abs-list">
                        <?php foreach ($alphas as $letter): ?>
                            <li><a class="profile-author-letter" href="<?= Url::to([$category->url_key, 'filter' => $letter]) ?>"><span class="text"><?= $letter ?></span></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="sidebar-widget">
                <div class="widget-title">data & methods</div>
                <div class="data-method-list">
                    <a href="/data-sources" class="data-method-item">
                        <div class="img"><img src="/images/temp/articles/01-img.jpg" alt=""></div>
                        <div class="caption">
                            <span class="icon-arrow-square-blue">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </span>
                            <h3>Looking for economic data sets?</h3>
                        </div>
                    </a>
                    <a href="/methods" class="data-method-item">
                        <div class="img"><img src="/images/temp/articles/02-img.jpg" alt="" width="430" height="326"></div>
                        <div class="caption">
                            <span class="icon-arrow-square-blue">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </span>
                            <h3>Want to learn more about empirical methods?</h3>
                        </div>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>