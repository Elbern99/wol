<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>

<ul class="post-list media-list">
    <?php foreach ($opinions as $item) : ?>
    <?php $opinion = $item->opinion; ?>
    <li class="post-item media-item">
        <?php $hasImage = $opinion->image_link ? true : false; ?>
        <?php if ($hasImage) : ?>
        <?= Html::beginTag('a', [
            'href' => Url::to(['/opinion/view', 'slug' => $opinion->url_key]),
            'class' => 'img',
            'style' => 'background-image: url(/uploads/opinions/' . $opinion->image_link .')',
        ]); ?>
        <?= Html::endTag('a'); ?>
        <?php endif; ?>
        <div class="writers"><span class="writer-item"></span></div>
        <h2>
            <?= Html::a($opinion->title, ['opinion/view', 'slug' => $opinion->url_key]); ?>
        </h2>
    </li>
    <?php endforeach; ?>

</ul>
<?php if ($opinionsCount > $opinionLimit): ?>
<?php $params = ['/topic/opinions', 'opinion_limit' => $opinionLimit, 'topic_id' => $topicId]; ?>
<?= Html::a("show more", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-opinions']) ?>
<?php else: ?>
    <?php if (Yii::$app->request->get('opinion_limit')): ?>
         <?php $params = ['/topic/opinions', 'topic_id' => $topicId]; ?>
        <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-opinions']) ?>
    <?php endif; ?>
<?php endif; ?>