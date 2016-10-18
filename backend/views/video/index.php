<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\helpers\AdminFunctionHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'Video');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><a class="btn btn-default" role="button" href="<?= Url::toRoute('view')?>"><?= Yii::t('app/menu', 'Add Video') ?></a></p>
    
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php \yii\widgets\Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    'url_key',
                    'video',
                    [
                        'attribute' => 'description',
                        'value' => function($model) {
                            return AdminFunctionHelper::short($model->description);
                        }
                    ],
                    'order',
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