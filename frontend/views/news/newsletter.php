<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\components\filters\NewsletterArchiveWidget;
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
<div class="container newsletter-page">
    <div class="breadcrumbs">
        <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
    </div>
    <div class="content-inner">
        <div class="content-inner-text">
            <?= $model->main ?>
        </div>
        <aside class="sidebar-right">
            <div class="sidebar-widget">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">newsletters</a>
                        <div class="text">
                            <?= NewsletterArchiveWidget::widget(['data' => $newsletterArchive]); ?>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
</div>

