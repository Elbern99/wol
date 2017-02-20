<?php
use yii\helpers\Html;
use frontend\components\widget\CustomGridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use frontend\models\AdvancedSearchForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.$page->Cms('meta_title');
$this->params['breadcrumbs'][] = Html::encode($page->Cms('title'));

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($page->Cms('meta_keywords'))
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($page->Cms('meta_description'))
]);

$items = $searchModel->getItems();
$items[0] = 'Show All';

$this->registerJsFile('/js/pages/sources.js', ['depends' => ['yii\web\YiiAsset']]);
?>

<?php $alphas = range('A', 'Z'); ?>

<div class="container without-breadcrumbs sources-page">
    <h1><?= Html::encode($page->Cms('meta_title')) ?></h1>

    <div class="content-inner">
        <div class="content-inner-text">
            <?= $page->Page('text') ?>
            <div class="source-table-holder">
                <?=
                CustomGridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    /*'pager' => [
                        'class' => \kop\y2sp\ScrollPager::className(),
                        'container' => '.grid-view tbody',
                        'item' => 'tr',
                        'paginationSelector' => '.grid-view .pagination',
                        'triggerTemplate' => '',
                        'triggerOffset' => 100,
                        'noneLeftText' => '',
                        'spinnerTemplate' => '<div class="preloader-table"><div class="loading-ball"></div></div>'
                     ],*/
                    'beforeRow' => function($model, $key, $index, $grid) {
                         
                        $currentLetter = strtoupper(substr($model->source, 0, 1));
                        $previosLetterGet = Yii::$app->request->get('previosLetter');
                        
                        if (!$grid->previosLetter && $previosLetterGet) {
                            $grid->previosLetter = $previosLetterGet;
                        }
                        
                        if ($grid->previosLetter != $currentLetter && preg_match("/[A-Z]{1}/", $currentLetter)) {
                            //$grid->dataProvider->getPagination()->params = array_merge(Yii::$app->request->get(), ['previosLetter' => $currentLetter]);
                            $grid->previosLetter = $currentLetter;
                            return Html::tag('tr', "<td colspan=3 class='td-letter'>".Html::a($currentLetter, '#'.$currentLetter, ['id' => $currentLetter])."</td>");
                        }
                    },
                    'columns' => [
                        [
                            'label' => 'Data Source',
                            'format' => 'raw',
                            'attribute' => 'source',
                            'value' => function($model) {
                                $text = "<br>";
                                $text .= '<a href="#" data-source="'.$model->source.'" class="search-source-article">View articles referencing this data source</a>';
                                return $model->source.$text;
                            },
                        ],
                        [
                            'format' => 'raw',
                            'attribute' => 'website',
                            'value' => function($model) {
                                return Html::a($model->website, $model->website, ['target'=>'_blank']);
                            },
                        ],
                        [
                            'filter' => $items,
                            'format' => 'html',
                            'label' => 'Type',
                            'attribute' => 'sourceTaxonomies',
                            'value' => function($model) {

                                $text = '<ul>';

                                foreach ($model->sourceTaxonomies as $taxonomy) {

                                    if (!isset($taxonomy->taxonomy->value)) {
                                        continue;
                                    }

                                    $source = $taxonomy->taxonomy->value;

                                    if (isset($taxonomy->additionalTaxonomy->value)) {
                                        $source .= ' - '.$taxonomy->additionalTaxonomy->value;
                                    }

                                    $text .= Html::tag('li', $source);
                                }

                                $text .= '</ul>';

                                return $text;
                            },
                        ]
                    ],
                ]);
                ?>
            </div>
        </div>
        <?php $model = new AdvancedSearchForm(); ?>
        <?php $form = ActiveForm::begin(['action'=>'/search', 'options' => ['class' => 'source-search-form', 'style' => 'display:none']]); ?>
            <?= $form->field($model, 'search_phrase') ?>
        <?php ActiveForm::end(); ?>
        <aside class="sidebar-right stiky">
            <div class="sidebar-widget">
                <div class="widget-title">Filter</div>
                <ul class="abs-list">
                    <?php foreach ($alphas as $letter): ?>
                        <li><a class="profile-author-letter" href="<?= Url::to('#'.$letter) ?>"><span class="letter"><?= $letter ?></span></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </div>

</div>

<div class="back-to-top"><div class="icon-upwards-arrow"></div></div>