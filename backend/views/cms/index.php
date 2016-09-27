<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\helpers\AdminFunctionHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'CMS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><a class="btn btn-default" role="button" href="<?= Url::toRoute('view')?>"><?= Yii::t('app/menu', 'Add Page') ?></a></p>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'url',
                    'cmsPageInfos.title',
                    'cmsPageInfos.meta_title',
                    [
                        'attribute' => 'cmsPageInfos.meta_keywords',
                        'value' => function($model) {
                            return AdminFunctionHelper::short($model->cmsPageInfos->meta_keywords);
                        }
                    ],
                    [
                        'attribute' => 'cmsPageInfos.meta_description',
                        'value' => function($model) {
                            return AdminFunctionHelper::short($model->cmsPageInfos->meta_description);
                        }
                    ],
                    'enabled',
                    [
                        'attribute' => 'created_at',
                        'value' => function($model) {
                            return AdminFunctionHelper::dateFormat($model->created_at);
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{delete}',
                        'header' => 'Actions',
                    ]
                ],
            ]);
            ?>
        </div>
    </div>
</div>