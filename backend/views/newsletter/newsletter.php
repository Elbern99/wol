<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\helpers\AdminFunctionHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'Newsletter');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <!--<p><a class="btn btn-default" role="button" href="<?= Url::toRoute('/newsletter/subscribers-export')?>"><?= Yii::t('app/menu', 'Export (xls)') ?></a></p>-->
    
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'email',
                    'first_name',
                    'last_name',
                    [
                        'attribute' => 'date',
                        'value' => function($model) {
                            return AdminFunctionHelper::dateFormat($model->created_at);
                        }
                    ],
                    [
                        'attribute' => 'interest',
                        'label' => 'Area of interest'
                    ],
                    [
                        'format' => 'html',
                        'attribute' => 'areas_interest',
                        'label' => 'Area of interest',
                        'value' => function($model) use ($areas) {
                            
                            $values = [];
                            
                            if ($model->areas_interest) {
                                
                                $categories = explode(',', $model->areas_interest);

                                foreach ($categories as $interest) {
                                    
                                    if (isset($areas[$interest])) {
                                        $values[] = $areas[$interest];
                                    }
                                }
                            }
                            
                            return implode("<br>" , $values);
                        }
                    ],
                    [
                        'attribute' => 'iza_world',
                        'label' => 'IZA World of Labor'
                    ],
                    [
                        'attribute' => 'iza',
                        'label' => 'IZA'
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{subscriber-delete}',
                        'header' => 'Actions',
                        'buttons' => [
                            'subscriber-delete' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                                    'data-method' => 'post',
                                ]);
                            },
                        ]
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>