<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\helpers\AdminFunctionHelper;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'News');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><a class="btn btn-default" role="button" href="<?= Url::toRoute('view')?>"><?= Yii::t('app/menu', 'Add News Item') ?></a></p>
    
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php \yii\widgets\Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'title',
                    'url_key',
                    [
                        'format' => 'datetime',
                        'filter' =>  'From:'.DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_at_from',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                'endDate'=>date('Y-m-d')
                            ]
                        ]).
                        'To:'.DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_at_to',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                'endDate'=>date('Y-m-d')
                            ]
                        ]),
                        'label' => 'Created At',
                        'value' => function ($model) {
                            return date('Y-M-d', $model->created_at->getTimestamp());
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{delete}',
                        'header' => 'Actions'
                    ],
                   
                ],
            ]);
            ?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>