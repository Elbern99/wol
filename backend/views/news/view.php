<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\tabs\TabsX;

$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'News'), 'url' => Url::toRoute('/news')];
$this->params['breadcrumbs'][] = $this->title;

$items[] = ['label' => '<i class="glyphicon"></i> '.Yii::t('app/text','News'),
        'content' => $this->renderFile(__DIR__.'/tabs/view.php', ['model' => $model])];


if ($widgets) {
    array_push($items, [
        'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Widgets'),
        'content' => $this->renderFile(__DIR__.'/tabs/widget.php', $widgets)
    ]);
}

?>

<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?=
            TabsX::widget([
               'items' => $items,
               'position' => TabsX::POS_LEFT,
               'encodeLabels' => false
           ]);
           ?>
        </div>
    </div>
</div>