<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<?php
$this->title = 'Key Topics';
$this->params['breadcrumbs'][] = $this->title;

if ($category) {
    $this->registerMetaTag([
        'name' => 'keywords',
        'content' => Html::encode($category->meta_keywords)
    ]);
    $this->registerMetaTag([
        'name' => 'title',
        'content' => Html::encode($category->meta_title)
    ]);
}
?>
<div class="container key-topics-page">
    <div class="content-inner">
        <div class="content-inner-text">
            <div class="breadcrumbs">
                <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
            </div>
            <h1>Key topics</h1>
            <?php Pjax::begin(['linkSelector' => '.btn-gray']); ?>
            <ul class="key-topics-list">
                <?php foreach ($topics as $topic) : ?>
                <?php $hasImage = $topic['image_link'] ? true : false; ?>
                <li>
                    <?php if ($hasImage) : ?>
                    <?= Html::beginTag('a', [
                        'href' => Url::to(['/topic/view', 'slug' => $topic['url_key']]),
                        'class' => 'key-topics-item has-image',
                        'style' => 'background-image: url(/uploads/topics/' . $topic['image_link']  .')',
                    ]) ?>
                    <?php else : ?>
                        <?=
                        Html::beginTag('a', [
                            'href' =>  Url::to(['/topic/view', 'slug' => $topic['url_key']]),
                            'class' => 'key-topics-item',
                        ])
                        ?>
                    <?php endif; ?>
                    <div class="caption">
                        <h2><?= $topic['title']; ?></h2>
                        <?php if (!$hasImage) : ?>
                        <p><?= $topic['short_description']; ?> </p>
                        <?php endif; ?>
                    </div>
                    <?= Html::endTag('a'); ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php if ($topicsCount > $limit): ?>
                    <?php $params = ['/topic/index', 'limit' => $limit]; ?>
                    <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray align-center']) ?>
            <?php else: ?>
                <?php if (Yii::$app->request->get('limit')): ?>
                     <?php $params = ['/topic/index']; ?>
                    <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray align-center']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>