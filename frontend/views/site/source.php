<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
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
?>

<?php $alphas = range('A', 'Z'); ?>
<ul class="abs-list">
    <?php foreach ($alphas as $letter): ?>
        <li><a class="profile-author-letter" href="<?= Url::to('#'.$letter) ?>"><span class="letter"><?= $letter ?></span></a></li>
    <?php endforeach; ?>
</ul>

<div class="container without-breadcrumbs sources-page">
    <h1><?= Html::encode($page->Cms('meta_title')) ?></h1>
    <div class="col-sm-12 sidenav">
        <?= $page->Page('text') ?>
    </div>
    <div class="source-table-holder">
        <?php \yii\widgets\Pjax::begin(); ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'beforeRow' => function($model, $key, $index, $grid) {
                if (!isset($grid->options['previosLetter'])) {
                    $grid->options['previosLetter'] = false;
                }
                $currentLetter = substr($model->source, 0, 1);
                
                if ($grid->options['previosLetter'] != $currentLetter) {
                    $grid->options['previosLetter'] = $currentLetter;
                    return Html::tag('tr', "<td colspan=3>".Html::a($currentLetter, '#'.$currentLetter, ['id' => $currentLetter])."</td>");
                }
            },
            'columns' => [
                [
                    'label' => 'Data Source',
                    'format' => 'html',
                    'attribute' => 'source',
                    'value' => function($model) {
                        $text = "<br>".Html::a('View articles referencing this data source', Url::to(['/search/advanced', 'phrase' => $model->source]));
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
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>