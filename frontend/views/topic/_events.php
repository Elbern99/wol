<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>

<ul class="post-list media-list">
    <?php foreach ($events as $item) : ?>
    <?php $event = $item->event; ?>
    <li class="post-item media-item">
        <?php $hasImage = $event->image_link ? true : false; ?>
        <?php if ($hasImage) : ?>
        <?= Html::beginTag('a', [
            'href' => Url::to(['/event/view', 'slug' => $event->url_key]),
            'class' => 'img',
            'style' => 'background-image: url(/uploads/events/' . $event->image_link .')',
        ]); ?>
        <?= Html::endTag('a'); ?>
        <?php endif; ?>
        <div class="date">
            <?= $event->date_from->format('F d, Y'); ?> - 
            <?= $event->date_to->format('F d, Y'); ?>
        </div>
        <h2>
            <?= Html::a($event->title, ['event/view', 'slug' => $event->url_key]); ?>
        </h2>
        <div class="event-location"><?= $event->location; ?></div>
    </li>
    <?php endforeach; ?>
</ul>

<?php if ($eventsCount > $eventLimit): ?>
<?php $params = ['/topic/events', 'event_limit' => $eventLimit, 'topic_id' => $topicId]; ?>
<?= Html::a("show more", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-events']) ?>
<?php else: ?>
    <?php if (Yii::$app->request->get('event_limit')): ?>
         <?php $params = ['/topic/events', 'topic_id' => $topicId]; ?>
        <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-events']) ?>
    <?php endif; ?>
<?php endif; ?>