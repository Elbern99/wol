<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>

<ul class="post-list media-list">
    <?php foreach ($videos as $item) : ?>
    <?php $video = $item->video; ?>
    <li class="post-item media-item">
        <?= Html::beginTag('a', [
            'href' => Url::to(['/video/view', 'slug' => $video->url_key]),
            'class' => 'img',
            'style' => "background-image: url('".$video->getVideoImageLink()."')",
        ]); ?>
        <div class="icon-play"></div>
        <?= Html::endTag('a'); ?>
        <h2>
            <?= Html::a($video->title, ['video/view', 'slug' => $video->url_key]); ?>
        </h2>
    </li>
    <?php endforeach ;?>
</ul>

<?php if ($videosCount > $videoLimit): ?>
<?php $params = ['/topic/videos', 'video_limit' => $videoLimit, 'topic_id' => $topicId]; ?>
<?= Html::a("show more", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-videos']) ?>
<?php else: ?>
    <?php if (Yii::$app->request->get('video_limit')): ?>
         <?php $params = ['/topic/videos', 'topic_id' => $topicId]; ?>
        <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-videos']) ?>
    <?php endif; ?>
<?php endif; ?>