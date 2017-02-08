<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'Editorial Board';
$this->params['breadcrumbs'][] = ['label' => Html::encode('About'), 'url' => Url::to(['/about'])];
$this->params['breadcrumbs'][] = Html::encode('Editorial Board');

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

    <h1>Editorial Board</h1>

    <div class="content-inner">
        <div class="content-inner-text">
            <?php if (count($top)): ?>
            <ul class="editors-list">
                <?php foreach($top as $author): ?>
                    <li class="editor-item">
                        <div class="img-holder img-holder-bg">
                            <div class="img" style="background-image: url(<?= $author['avatar'] ?>)"></div>
                            <?= $author['avatar'] ?>
                        </div>
                        <div class="name">
                            <a href="<?= $author['profile'] ?>">
                                <?= $author['name']->first_name ?>
                                <?= $author['name']->middle_name ?>
                                <?= $author['name']->last_name ?>
                            </a>
                        </div>
                        <div class="vacancy"><?= $author['interest'] ?></div>
                        <p><?= $author['affiliation'] ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            
            <?php if (isset($collection['subjectEditor'])): ?>
            <h2><?= Html::encode('Subject Editors') ?></h2>
            <ul class="editors-list">
                <?php foreach($collection['subjectEditor'] as $author): ?>
                    <li class="editor-item">
                        <div class="img-holder img-holder-bg">
                            <div class="img" style="background-image: url(<?= $author['avatar'] ?>)"></div>
                            <?= $author['avatar'] ?>
                        </div>
                        <div class="name">
                            <a href="<?= $author['profile'] ?>">
                                <?= $author['name']->first_name ?>
                                <?= $author['name']->middle_name ?>
                                <?= $author['name']->last_name ?>
                            </a>
                        </div>
                        <div class="vacancy"><?= $author['interest'] ?></div>
                        <p><?= $author['affiliation'] ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            
            <?php if (isset($collection['associateEditor'])): ?>
            <h2><?= Html::encode('Associate Editors') ?></h2>
            <ul class="editors-list">
                <?php foreach($collection['associateEditor'] as $author): ?>
                    <li class="editor-item">
                        <div class="img-holder img-holder-bg">
                            <div class="img" style="background-image: url(<?= $author['avatar'] ?>)"></div>
                            <?= $author['avatar'] ?>
                        </div>
                        <div class="name">
                            <a href="<?= $author['profile'] ?>">
                                <?= $author['name']->first_name ?>
                                <?= $author['name']->middle_name ?>
                                <?= $author['name']->last_name ?>
                            </a>
                        </div>
                        <div class="vacancy"><?= $author['interest'] ?></div>
                        <p><?= $author['affiliation'] ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            
            <?php if (isset($collection['formerEditor'])): ?>
            <h2><?= Html::encode('Former Editors') ?></h2>
            <?= $widgets->getPageWidget('former_editor_thanks') ?>
            <ul class="editors-list">
                <?php foreach($collection['formerEditor'] as $author): ?>
                    <li class="editor-item">
                        <div class="img-holder img-holder-bg">
                            <div class="img" style="background-image: url(<?= $author['avatar'] ?>)"></div>
                            <?= $author['avatar'] ?>
                        </div>
                        <div class="name">
                            <a href="<?= $author['profile'] ?>">
                                <?= $author['name']->first_name ?>
                                <?= $author['name']->middle_name ?>
                                <?= $author['name']->last_name ?>
                            </a>
                        </div>
                        <div class="vacancy"><?= $author['interest'] ?></div>
                        <p><?= $author['affiliation'] ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            
            <?= $widgets->getPageWidget('editorial_board_widget') ?>
        </div>

        <aside class="sidebar-right">
            <?php foreach ($widgets->getPageWidgets(['editorial_board_widget', 'former_editor_thanks']) as $widget): ?>
                <?= $widget ?>
            <?php endforeach; ?>
        </aside>
    </div>
</div>