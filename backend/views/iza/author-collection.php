<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\tabs\TabsX;

$this->title = Yii::t('app.menu', 'Author');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Author'), 'url' => Url::to('authors')];
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
                        'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Author'),
                        'content' => $this->renderFile(__DIR__.'/author/table.php', ['model' => $authorModel])
                    ],
                    [
                        'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Attributes'),
                        'content' => $this->renderFile(__DIR__.'/article/attributes.php', ['collection' => $collection])
                    ],
                ],
               'position' => TabsX::POS_LEFT,
               'encodeLabels' => false
           ]);
           ?>
        </div>
    </div>
</div>