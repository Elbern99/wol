<?php
use frontend\components\search\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Result;
use frontend\components\filters\ContentTypesWidget;
use frontend\components\filters\SubjectAreasWidget;
use frontend\components\filters\BiographyWidget;
use frontend\components\filters\TopicsWidget;
use yii\widgets\ActiveForm;
?>

<?php
$this->title = 'Search Result For ';
$this->params['breadcrumbs'][] = Html::encode($this->title);

$this->registerJsFile('/js/plugins/mark.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/jquery.mark.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/pages/advanced-search.js', ['depends' => ['yii\web\YiiAsset']]);
?>

<div class="container search-results">

    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
        <h1>Search the site</h1>
    </div>

    <?php $form = ActiveForm::begin(['action' => Url::to(['/search', 'phrase' => $phrase]), 'options' => ['class'=>'result-search']]); ?>
        <div class="search-results-top">
            <div class="search">
                <a href="/search/save" class="btn-border-blue-large with-icon-r btn-save-search">
                    <span class="icon-save"></span>
                    <div class="btn-save-search-inner">search saved to your account</div>
                </a>
                <div class="search-bottom">
                    <a href="<?= Url::to(['/search/advanced']) ?>">advanced search</a>
                </div>
                <div class="search-top">
                    <span class="icon-search"></span>
                    <button type="submit" class="btn-blue btn-center">
                        <span class="inner">
                            search
                        </span>
                    </button>
                    <div class="search-holder">
                        <?= $form->field($search, 'search_phrase')->textInput(['class'=>"form-control-decor", 'placeholder'=>"Keyword(s) or name"])->label('') ?>
                    </div>
                </div>
            </div>
            <div class="search-results-top-text">
                Your search for <strong><?=$phrase?></strong> returned <strong><?=$resultCount?></strong> results <a href="<?= Url::to(['/search/refine']) ?>" class="refine-link">Refine</a>
            </div>
            <div class="mobile-filter-holder">
                <div class="search-results-top-filter">
                    <strong><?=$resultCount?> results</strong>
                    <a href="" class="filter-mobile-link">Filter</a>
                    <a href="<?= Url::to(['/search/refine']) ?>" class="refine-mobile-link">Refine</a>
                </div>
                <div class="mobile-filter">
                    <div class="mobile-filter-container"></div>
                    <div class="mobile-filter-bottom">
                        <button type="submit" class="btn-blue-large">update</button>
                        <a href="" class="btn-no-style">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-inner">
            <div class="content-inner-text">
                
                <?php if(count($topData)): ?>
                    <ul class="search-results-media-list">
                    <?php foreach ($topData as $params): ?>
                        <?= $this->render("items/".$params['type'].".php",['value' => $params['params'], 'type' => $params['type']]); ?>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                
                <div class="search-results-table">
                    <div class="search-results-table-top">
                        <div class="search-results-item article-item">
                            <div class="publication-date">
                                Publication date
                            </div>
                            <div class="type">
                                Type
                            </div>
                            <div class="description-center">
                                Description
                            </div>
                        </div>
                    </div>

                    <div class="search-results-table-body">
                        <?php foreach ($resultData as $item): ?>
                            <?= $this->render("items/".$item['type'].".php",['value' => $item['params'], 'type' => $item['type']]); ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php $requestCount = Yii::$app->request->get('count') ?>
                <div class="pagination-holder">

                    <div class="pagination-select">
                        <div class="label-text">show</div>
                        <div class="custom-select dropdown">
                            <div class="custom-select-title dropdown-link">10</div>
                            <div class="sort-list drop-content">
                                <div <?= $requestCount == 10 ? 'data-select="selected"' : '' ?>><a  href="<?=Url::current(['count' => 10, 'phrase' => $phrase]) ?>">10</a></div>
                                <div <?= $requestCount == 25 ? 'data-select="selected"' : '' ?>><a  href="<?=Url::current(['count' => 25, 'phrase' => $phrase]) ?>">25</a></div>
                                <div <?= (!$requestCount || $requestCount == 50) ? 'data-select="selected"' : '' ?>><a  href="<?=Url::current(['count' => 50, 'phrase' => $phrase]) ?>">50</a></div>
                                <div <?= $requestCount == 100 ? 'data-select="selected"' : '' ?>><a  href="<?=Url::current(['count' => 100, 'phrase' => $phrase]) ?>">100</a></div>
                            </div>
                        </div>
                    </div>

                    <?= LinkPager::widget([
                            'pagination' => $paginate,
                            'options' => ['class' => 'pagination'],
                            'nextPageLabel' => 'Next',
                            'prevPageLabel' => 'Previous',
                            'lastPageLabel' => true,
                            'maxButtonCount' => 9
                        ]);
                    ?>
                </div>

            </div>

            <aside class="sidebar-right">

                <div class="sidebar-widget sidebar-widget-save">
                    <a href="/search/save" class="btn-border-blue-large with-icon btn-save-search">
                        <span class="inner">
                            <span class="icon-save"></span>
                            <span class="text">save search</span>
                        </span>
                        <div class="btn-save-search-inner">search saved to your account</div>
                    </a>
                </div>

                <div class="filter-clone-holder">
                    <div class="filter-clone">
                        <div class="sidebar-widget sidebar-widget-sort-by">
                            <label>sort by</label>
                            <div class="custom-select dropdown">
                                <div class="custom-select-title dropdown-link">
                                    Publication date (descending)
                                </div>
                                <div class="sort-list drop-content">
                                    <div>
                                        <a href="<?=Url::current(['sort' => 0, 'phrase' => $phrase]) ?>">Publication date (descending)</a>
                                    </div>
                                    <div <?= Yii::$app->request->get('sort') ? 'data-select="selected"' : '' ?>>
                                        <a href="<?=Url::current(['sort' => 1, 'phrase' => $phrase]) ?>">Publication date (ascending)</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-widget sidebar-widget-filter">
                            <h3>Filter results by</h3>
                            <ul class="sidebar-accrodion-list">
                                <li class="sidebar-accrodion-item is-open">
                                    <a href="" class="title">content types <strong>(<?= $resultCount ?>)</strong></a>
                                    <div class="text">
                                        <?= ContentTypesWidget::widget(['param' => $filters['types']]); ?>
                                        <a href="" class="clear-all">Clear all</a>
                                    </div>
                                </li>
                                <li class="sidebar-accrodion-item">
                                    <a href="" class="title">subject areas <strong>(<?= array_sum(Result::$articleCategoryIds) ?>)</strong></a>
                                    <div class="text">
                                        <?= SubjectAreasWidget::widget(['param' => $filters['category']]); ?>
                                        <a href="" class="clear-all">Clear all</a>
                                    </div>
                                </li>
                                <li class="sidebar-accrodion-item">
                                    <a href="" class="title">Authors <strong>(<?= count($filters['biography']['data']) ?>)</strong></a>
                                    <div class="text">
                                        <?= BiographyWidget::widget(['param' => $filters['biography']]); ?>
                                        <a href="" class="clear-all">Clear all</a>
                                    </div>
                                </li>
                                <li class="sidebar-accrodion-item">
                                    <a href="" class="title">Topics <strong>(<?= count($filters['topics']['data']) ?>)</strong></a>
                                    <div class="text">
                                        <?= TopicsWidget::widget(['param' => $filters['topics']]); ?>
                                        <a href="" class="clear-all">Clear all</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    <?php ActiveForm::end(); ?>
</div>


