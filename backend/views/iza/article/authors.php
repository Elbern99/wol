<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="col-sm-12">
<h3>Add Author</h3>
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($articleAuthorForm, 'author_key') ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/form', 'ADD'), ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>

<?=
GridView::widget([
    'dataProvider' => $query,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'author.author_key',
        'author.name',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{article-author-delete}',
            'header' => 'Actions',
            'buttons' => [
                'article-author-delete' => function ($url, $model) {
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