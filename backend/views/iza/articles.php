<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\helpers\AdminFunctionHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php \yii\widgets\Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'seo',
                        'value' => function($model) {
                            return AdminFunctionHelper::short($model->seo);
                        }
                    ],
                    [
                        'attribute' => 'doi',
                        'value' => function($model) {
                            return AdminFunctionHelper::short($model->doi);
                        }
                    ],
                    'publisher',
                    'availability',
                    [
                        'attribute' => 'sort_key',
                        'value' => function($model) {
                            return AdminFunctionHelper::short($model->sort_key);
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function($model) {
                            return AdminFunctionHelper::dateFormat($model->created_at);
                        }
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => function($model) {
                            return AdminFunctionHelper::dateFormat($model->updated_at);
                        }
                    ],
                    [
                        'attribute' => 'enabled',
                        'format' => 'raw',
                        'value' => function($model) {
                            return Html::activeCheckbox($model, 'enabled', ['class'=>'enabled_field', 'data-item'=>$model->id]);
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{article-view}',
                        'header' => 'Actions',
                        'buttons' => [
                            'article-view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'View'),
                                ]);
                            }
                        ]
                    ],
                ],
            ]);
            ?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>