<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\helpers\AdminFunctionHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><a class="btn btn-default" role="button" href="<?= Url::toRoute('view')?>"><?= Yii::t('app/menu', 'Add Event') ?></a></p>
    
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
                    'location',
                    [
                        'format' => 'raw',
                        'label' => 'Date From',
                        'value' => function ($model) {
                            $date = new \DateTime($model->date_from);
                            return $date->format('d F Y');
                        }
                    ],
                    [
                        'format' => 'raw',
                        'label' => 'Date To',
                        'value' => function ($model) {
                            $date = new \DateTime($model->date_to);
                            return $date->format('d F Y');
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