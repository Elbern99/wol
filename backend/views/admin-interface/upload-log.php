<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\helpers\AdminFunctionHelper;
use \backend\models\ArchiveLog;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'Log');
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
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    [
                        'attribute' => 'created_at',
                        'value' => function($model) {
                            if ($model->status == ArchiveLog::STATUS_DONE) {
                                return 'OK';
                            }
                            
                            return 'Errors';
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function($model) {
                            return AdminFunctionHelper::dateFormat($model->created_at);
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{upload-log-view}{upload-log-delete}',
                        'header' => 'Log',
                        'buttons' => [
                            'upload-log-view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'Edit'),
                                ]);
                            },
                            'upload-log-delete' => function ($url, $model) {
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
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>