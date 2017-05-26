<?php 
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$dataProvider->sort = false;
?>
<div class="tab js-tab-hidden" id="tab-3">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' =>'save-search-table'],
        'columns' => [
            'search_phrase',
            'all_words',
            'any_words',
            [
                'attribute' => 'types',
                'value' => function($model) {
                    return implode(', ', $model->getTypeNamesByIds());
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{search}{search-delete}',
                'header' => '',
                'buttons' => [
                    'search' => function ($url, $model) {
                        $params = [
                            'search_phrase' => $model->search_phrase,
                            'types' => explode(',', $model->types),
                            'all_words' => $model->all_words,
                            'any_words' => $model->any_words,
                        ];

                        return Html::a('Search', Url::to(array_merge(['/search'],$params)), [
                            'title' => Yii::t('app', 'Search'), 'class'=>'btn-blue', 'target'=>'blank'
                        ]);
                    },
                    'search-delete' => function ($url, $model) {
                        return Html::a('<span class="icon-trash">', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                            'data-method' => 'post',
                            'class' => 'btn-border-gray-middle short'
                        ]);
                    },
                    
                ]
                
            ]
        ],
    ]);
    ?>
</div>
