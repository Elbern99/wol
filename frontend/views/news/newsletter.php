<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.$model->title;
$this->params['breadcrumbs'][] =['label'=>'News', 'url'=>Url::to('/news', true)];
$this->params['breadcrumbs'][] = $model->title;


$this->registerMetaTag([
'name' => 'keywords',
'content' => Html::encode($this->title)
]);
$this->registerMetaTag([
    'name' => 'title',
    'content' => Html::encode($this->title)
]);

?>
<div class="container">
    <div class="breadcrumbs">
        <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
    </div>
    <div class="content-inner subscribe-page">
        <?= $model->main ?>
    </div>
</div>

