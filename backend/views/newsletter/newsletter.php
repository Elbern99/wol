<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\helpers\AdminFunctionHelper;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $logId integer */

$this->title = Yii::t('app.menu', 'Newsletter');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <a
            class="btn btn-default"
            role="button"
            href="<?= Url::toRoute(['newsletter/subscribers-export', 'logs_id' => $logId])?>">
                <?= Yii::t('app/menu', 'Export') ?>
        </a>
    </p>
    
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
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
                        'label' => 'Area of interest',
                        'filter' => Html::DropDownList( 'SubscribersSearch[interest]', Yii::$app->request->get( 'SubscribersSearch' )['interest'], [
                            '' => 'Select',
                            0 => 0,
                            1 => 1
                        ] )

                    ],
                    [
                        'format' => 'html',
                        'attribute' => 'areas_interest',
                        'label' => 'Area of interest',
                        'value' => function($model) use ($areas) {

                            $values = [];

                            if ($model->areas_interest) {

                                $categories = explode(',', $model->areas_interest);

                                foreach ($categories as $id => $interest) {

                                    if (isset($areas[$interest])) {
                                        $values[$id] = $areas[$interest];
                                        $cats[$id] = $areas[$interest];

                                    }
                                }
                            }

                            return implode("<br>" , $values);
                        },
                        'filter' => Html::DropDownList( 'SubscribersSearch[areas_interest]', Yii::$app->request->get( 'SubscribersSearch' )['areas_interest'], $cats )
                    ],
                    [
                        'attribute' => 'iza_world',
                        'label' => 'IZA World of Labor'
                    ],
                    [
                        'attribute' => 'iza',
                        'label' => 'IZA',
                        'filter' => Html::DropDownList( 'SubscribersSearch[iza]', Yii::$app->request->get( 'SubscribersSearch' )['iza'], [
                            '' => 'Select',
                            0 => 0,
                            1 => 1
                        ] )

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
