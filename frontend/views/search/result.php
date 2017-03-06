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
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'Search Result For ';
$this->params['breadcrumbs'][] = Html::encode('Search Result For ');

$this->registerJsFile('/js/plugins/mark.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/jquery.mark.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/pages/advanced-search.js', ['depends' => ['yii\web\YiiAsset']]);

$currentUrl[] = '/search';
$currentParams = Yii::$app->getRequest()->getQueryParams();
$currentUrl = array_merge($currentUrl, $currentParams);
unset($currentParams);
?>

<div class="container search-results">

    <div class="preloader">
        <div class="loading-ball"></div>
    </div>

    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
        <h1>Search the site</h1>
    </div>

    <?php $form = ActiveForm::begin(['action' => Url::to(['/search', 'phrase' => $phrase]), 'options' => ['class'=>'result-search']]); ?>
        <div class="search-results-top">
            <div class="search">
                <div class="save-search-holder hide-desktop">
                    <div class="save-search-alert">search saved to your account</div>
                    <a href="/search/save" class="btn-border-blue-large with-icon-r btn-save-search">
                        <span class="icon-save"></span>
                    </a>
                </div>
                <div class="search-bottom">
                    <a href="<?= Url::to(['/search/advanced', 'phrase' => $phrase]) ?>">advanced search</a>
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
                Your search for <strong><?=$phrase?></strong> returned <strong><?= $currentCountResult ?></strong> results <a href="<?= Url::to(['/search/refine']) ?>" class="refine-link">Refine</a>
            </div>
            <div class="mobile-filter-holder">
                <div class="search-results-top-filter">
                    <strong><?= $currentCountResult ?> results</strong>
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
        <?= Html::hiddenInput('result_page', 1) ?>
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
                        <div class="search-results-item">
                            <div class="publication-date">
                                Publication date
                            </div>
                            <div class="type">
                                Type
                            </div>
                            <div class="article-item">
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
                    <div class="save-search-holder">
                        <div class="save-search-alert">search saved to your account</div>
                        <a href="/search/save" class="btn-border-blue-large with-icon btn-save-search">
                        <span class="inner">
                            <span class="icon-save"></span>
                            <span class="text">save search</span>
                        </span>
                        </a>
                    </div>
                </div>

                <div class="filter-clone-holder">
                    <div class="filter-clone">
                        <div class="sidebar-widget sidebar-widget-sort-by">
                            <?= $this->renderFile('@frontend/views/search/order.php', ['currentUrl' => $currentUrl]); ?>
                        </div>

                        <div class="sidebar-widget sidebar-widget-filter">
                            <h3>Filter results by</h3>
                            <ul class="sidebar-accrodion-list">
                                <li class="sidebar-accrodion-item sidebar-accordion-item-subject-areas is-open">
                                    <a href="" class="title">subject areas <strong>(1169)</strong></a>
                                    <div class="text" style="display: block;">
                                        <ul class="checkbox-list no-more"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="373" checked="checked"><span class="label-text">Program evaluation<strong class="count">(58)</strong></span></label><ul class="subcheckbox-list"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="374"><span class="label-text">Occupational and&nbsp;classroom training<strong class="count">(10)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="375"><span class="label-text">Wage subsidies and&nbsp;in-work benefits<strong class="count">(8)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="376"><span class="label-text">Counseling, sanctioning, and monitoring<strong class="count">(7)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="377"><span class="label-text">Micro-credits and&nbsp;start-up subsidies<strong class="count">(3)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="378"><span class="label-text">Child-care support, early childhood education, and schooling<strong class="count">(24)</strong></span></label></li></ul></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="379" checked="checked"><span class="label-text">Behavioral and personnel economics<strong class="count">(50)</strong></span></label><ul class="subcheckbox-list"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="380"><span class="label-text">Pay and incentives<strong class="count">(31)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="381"><span class="label-text">Organization and hierarchies<strong class="count">(6)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="382"><span class="label-text">Human resource management practices<strong class="count">(23)</strong></span></label></li></ul></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="383" checked="checked"><span class="label-text">Migration and ethnicity<strong class="count">(72)</strong></span></label><ul class="subcheckbox-list"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="384"><span class="label-text">Labor mobility<strong class="count">(15)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="385"><span class="label-text">Performance of migrants<strong class="count">(25)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="386"><span class="label-text">Implications of migration<strong class="count">(40)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="387"><span class="label-text">Migration policy<strong class="count">(24)</strong></span></label></li></ul></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="388" checked="checked"><span class="label-text">Labor markets and institutions<strong class="count">(131)</strong></span></label><ul class="subcheckbox-list"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="389"><span class="label-text">Wage setting<strong class="count">(38)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="390"><span class="label-text">Insurance policies<strong class="count">(11)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="391"><span class="label-text">Redistribution policies<strong class="count">(32)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="392"><span class="label-text">Labor market regulation<strong class="count">(37)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="393"><span class="label-text">Entrepreneurship<strong class="count">(15)</strong></span></label></li></ul></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="394" checked="checked"><span class="label-text">Transition and emerging economies<strong class="count">(36)</strong></span></label><ul class="subcheckbox-list"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="395"><span class="label-text">Labor supply and demand<strong class="count">(8)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="396"><span class="label-text">Gender issues<strong class="count">(4)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="397"><span class="label-text">Demographic change and migration<strong class="count">(6)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="398"><span class="label-text">Institutions, policies, and labor market outcomes<strong class="count">(24)</strong></span></label></li></ul></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="399" checked="checked"><span class="label-text">Development<strong class="count">(44)</strong></span></label><ul class="subcheckbox-list"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="400"><span class="label-text">Active labor market programs<strong class="count">(9)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="401"><span class="label-text">Microfinance and financial regulations<strong class="count">(1)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="402"><span class="label-text">Technological change<strong class="count">(4)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="403"><span class="label-text">Social insurance<strong class="count">(10)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="404"><span class="label-text">Skills and training programs<strong class="count">(7)</strong></span></label></li></ul></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="405" checked="checked"><span class="label-text">Environment<strong class="count">(5)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="406" checked="checked"><span class="label-text">Education and human capital<strong class="count">(59)</strong></span></label><ul class="subcheckbox-list"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="407"><span class="label-text">Economic returns to education<strong class="count">(16)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="408"><span class="label-text">Social returns to education<strong class="count">(10)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="409"><span class="label-text">Schooling and higher education<strong class="count">(29)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="410"><span class="label-text">Vocational education, training skills, and lifelong learning<strong class="count">(12)</strong></span></label></li></ul></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="411" checked="checked"><span class="label-text">Demography, family, and gender<strong class="count">(84)</strong></span></label><ul class="subcheckbox-list"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="412"><span class="label-text">Demography<strong class="count">(12)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="413"><span class="label-text">Family<strong class="count">(34)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="414"><span class="label-text">Gender<strong class="count">(24)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="415"><span class="label-text">Health<strong class="count">(21)</strong></span></label></li></ul></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="416" checked="checked"><span class="label-text">Data and methods<strong class="count">(29)</strong></span></label><ul class="subcheckbox-list"><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="417"><span class="label-text">Data<strong class="count">(8)</strong></span></label></li><li class=""><label class="def-checkbox light item-filter-box"><input type="checkbox" name="filter_subject_type[]" value="418"><span class="label-text">Methods<strong class="count">(13)</strong></span></label></li></ul></li></ul>                                            <a href="" class="clear-all">Select all</a>
                                    </div>
                                </li>
                                <li class="sidebar-accrodion-item is-open sidebar-accordion-item-types">
                                    <a href="" class="title">content types <strong>(<?= $resultCount ?>)</strong></a>
                                    <div class="text">
                                        <?php if (isset($filters['types'])): ?>
                                            <?= ContentTypesWidget::widget(['param' => $filters['types']]); ?>
                                            <a href="" class="clear-all">Clear all</a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <li class="sidebar-accrodion-item sidebar-accordion-item-subject-areas">
                                    <a href="" class="title">subject areas <strong>(<?= array_sum(Result::$articleCategoryIds) ?>)</strong></a>
                                    <div class="text">
                                        <?php if (isset($filters['category'])): ?>
                                            <?= SubjectAreasWidget::widget(['param' => $filters['category']]); ?>
                                            <a href="" class="clear-all">Clear all</a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <li class="sidebar-accrodion-item">
                                    <a href="" class="title">Authors <strong>(<?= count($filters['biography']['data']) ?>)</strong></a>
                                    <div class="text">
                                        <?php if (isset($filters['biography'])): ?>
                                            <?= BiographyWidget::widget(['param' => $filters['biography']]); ?>
                                            <a href="" class="clear-all">Clear all</a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <li class="sidebar-accrodion-item">
                                    <a href="" class="title">Topics <strong>(<?= count($filters['topics']['data']) ?>)</strong></a>
                                    <div class="text">
                                        <?php if (isset($filters['topics'])): ?>
                                            <?= TopicsWidget::widget(['param' => $filters['topics']]); ?>
                                            <a href="" class="clear-all">Clear all</a>
                                        <?php endif; ?>
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


