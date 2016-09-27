<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use backend\helpers\AdminFunctionHelper;
?>

<div class="col-lg-12">
    <p>
        <a class="btn btn-default" role="button" href="<?= Url::toRoute(['cms/section-add','page_id'=>$page_id])?>"><?= Yii::t('app/menu', 'Add Section') ?></a>
    </p>
</div>
<div class="col-lg-12">
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'title',
                'value' => function($model) {
                    return AdminFunctionHelper::short($model->title, 20);
                }
            ],
            'anchor',
            'open',
            'order',
            [
                'attribute' => 'text',
                'value' => function($model) {
                    return AdminFunctionHelper::short($model->text);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{section-edit}{section-delete}',
                'header' => 'Actions',
                'buttons' => [
                    'section-edit' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'Edit'),
                        ]);
                    },
                    'section-delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                            'data-method' => 'post',
                        ]);
                    },
                    
                ]
                
            ]
        ],
    ]);
    ?>
</div>

