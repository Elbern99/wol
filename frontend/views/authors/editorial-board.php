<?php
use yii\helpers\Html;
?>

<?php
$this->title = 'Editorial Board';
$this->params['breadcrumbs'][] = Html::encode($this->title);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($this->title)
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($this->title)
]);
?>
<div class="container editorial-board-page">
    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
    </div>

    <h1><?= $this->title ?></h1>

    <div class="content-inner">
        <div class="content-inner-text">
            <?php foreach ($collection as $role=>$editors): ?>
                <h2><?= Yii::t('app/text',$roles->getTypeByKey($role)) ?></h2>
                <ul class="editors-list">
                    <?php foreach($editors as $author): ?>
                        <li class="editor-item">
                            <div class="img-holder img-holder-bg">
                                <div class="img" style="background-image: url(<?= $author['avatar'] ?>)></div>
                            </div>
                            <div class="name">
                                <a href="<?= $author['profile'] ?>">
                                    <?= $author['name']->first_name ?>
                                    <?= $author['name']->last_name ?>
                                    <?= $author['name']->middle_name ?>
                                </a>
                            </div>
                            <div class="vacancy"><?= $author['interest'] ?></div>
                            <p><?= $author['affiliation'] ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>

            <?= $widgets->getPageWidget('editorial_board_widget') ?>
        </div>

        <aside class="sidebar-right">
            <?php foreach ($widgets->getPageWidgets(['editorial_board_widget']) as $widget): ?>
                <?= $widget ?>
            <?php endforeach; ?>
        </aside>
    </div>
</div>