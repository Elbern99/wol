<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\tabs\TabsX;

$this->title = Yii::t('app.menu', 'Article');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Articles'), 'url' => Url::to('articles')];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?=
            TabsX::widget([
                'items' => [
                    [
                        'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Article'),
                        'content' => $this->renderFile(__DIR__.'/article/table.php', ['model' => $articleModel])
                    ],
                    [
                        'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Attributes'),
                        'content' => $this->renderFile(__DIR__.'/article/attributes.php', ['collection' => $collection])
                    ],
                    [
                        'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Upload File'),
                        'content' => $this->renderFile(__DIR__.'/article/file_upload.php', ['model' => $fileUploadModel])
                    ],
                ],
               'position' => TabsX::POS_LEFT,
               'encodeLabels' => false
           ]);
           ?>
        </div>
    </div>
</div>