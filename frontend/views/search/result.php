<?php
use frontend\components\search\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Result;
use frontend\models\SearchForm;
use frontend\components\filters\ContentTypesWidget;
use frontend\components\filters\SubjectAreasWidget;
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
    <div class="breadcrumbs">
        <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
    </div>
    <h1>Search the site</h1>

    <?php $form = ActiveForm::begin(['action' => Url::to(['/search', 'phrase' => $phrase])]); ?>
        <div class="search-results-top">
            <div class="search">
                <a href="" class="btn-border-blue-large with-icon-r btn-save-search">
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
                    <a href="" class="refine-mobile-link">Refine</a>
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
            <div class="content-inner-text contact-page">
                <!--<ul class="search-results-media-list">
                    <li class="search-results-media-item">
                        <div class="img">
                            <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                        </div>
                        <div class="link"><a href="">TOPIC</a></div>
                        <div class="name">The Chinese economy</div>
                    </li>
                    <li class="search-results-media-item">
                        <div class="img">
                            <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                        </div>
                        <div class="link"><a href="">AUTHOR</a></div>
                        <div class="name">Hartmut Lehmann</div>
                        <p>Professor of Economic Policy in the Department of Economics at the University of Bologna, Italy</p>
                    </li>
                </ul>-->

                <div class="search-results-table">
                    <div class="search-results-table-top">
                        <div class="search-results-item">
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
                        <?php foreach ($resultData as $result): ?>

                            <?php 
                                switch ($result['type']) {
                                    case 'article':
                                        if(isset(Result::$value[$result['type']][$result['id']])) {
                                           echo $this->render('items/article.php',['value' => Result::$value[$result['type']][$result['id']], 'type' => $result['type']]);
                                        }
                                    break;
                                }
                            ?>
                        
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php $requestCount = Yii::$app->request->get('count') ?>
                <div class="pagination">
                    
                    <div class="pagination-select">
                        <div class="label-text">show</div>
                        <label class="custom-select-def">
                            <a <?= $requestCount == 10 ? 'class="active"' : '' ?> href="<?=Url::current(['count' => 10]) ?>">10</a>
                            <a <?= $requestCount == 25 ? 'class="active"' : '' ?> href="<?=Url::current(['count' => 25]) ?>">25</a>
                            <a <?= (!$requestCount || $requestCount == 50) ? 'class="active"' : '' ?> href="<?=Url::current(['count' => 50]) ?>">50</a>
                            <a <?= $requestCount == 100 ? 'class="active"' : '' ?> href="<?=Url::current(['count' => 100]) ?>">100</a>
                        </label>
                    </div>
                    
                    <?= LinkPager::widget([
                            'pagination' => $paginate, 
                            'options' => ['class' => 'pagination-list'], 
                            'nextPageLabel' => 'Next', 
                            'prevPageLabel' => 'Previous',
                            'lastPageLabel' => true,
                            'maxButtonCount' => 9
                        ]); 
                    ?>
                </div>
                
            </div>

            <aside class="sidebar-right">
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
                                        <a href="<?=Url::current(['sort' => 0]) ?>">Publication date (descending)</a>
                                    </div>
                                    <div <?= Yii::$app->request->get('sort') ? 'data-select="selected"' : '' ?>>
                                        <a href="<?=Url::current(['sort' => 1]) ?>">Publication date (ascending)</a>
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
                                        <?= ContentTypesWidget::widget(['dataClass' => SearchForm::class, 'dataSelect' => Result::$formatData, 'filtered' => Result::getFilter('types')]); ?>
                                        <a href="" class="clear-all">Clear all</a>
                                    </div>
                                </li>
                                <li class="sidebar-accrodion-item">
                                    <a href="" class="title">subject areas <strong>(<?= array_sum(Result::$articleCategoryIds) ?>)</strong></a>
                                    <div class="text">
                                        <?= SubjectAreasWidget::widget(['category' => $subjectArea, 'selected' => Result::$articleCategoryIds, 'filtered' => Result::getFilter('subject')]); ?>
                                        <a href="" class="clear-all">Clear all</a>
                                    </div>
                                </li>
                                <li class="sidebar-accrodion-item">
                                    <a href="" class="title">key topics <strong>(39)</strong></a>
                                    <div class="text">
                                    </div>
                                    <a href="" class="clear-all">Clear all</a>
                                </li>
                                <li class="sidebar-accrodion-item">
                                    <a href="" class="title">authors <strong>(39)</strong></a>
                                    <div class="text">
                                    </div>
                                    <a href="" class="clear-all">Clear all</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    <?php ActiveForm::end(); ?>
</div>


