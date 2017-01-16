<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>

<ul class="commentary-videos-list">
    <?php foreach ($videos as $video) : ?> 
    <li class="">
        <div class="video-item has-image">

            <?= Html::beginTag('a', [
                'href' => Url::to(['/video/view', 'slug' => $video->url_key]),
                'class' => 'img',
                'style' => "background-image: url('".$video->getVideoImageLink()."')",
            ]); ?>
                <span class="icon-play"></span>
            <?= Html::endTag('a'); ?>
            <div class="desc">
                <div class="inner">
                    <h2>
                        <?= Html::a($video->title, ['/video/view', 'slug' => $video->url_key]); ?>
                    </h2>
                    <?php if ($video->description) : ?>
                    <p>
                       <?= $video->description; ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
<?php if ($videosCount > $videoLimit): ?>
        <?php $params = ['/commentary/videos', 'video_limit' => $videoLimit]; ?>
        <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-videos']) ?>
<?php else: ?>
    <?php if (Yii::$app->request->get('video_limit')): ?>
         <?php $params = ['/commentary/videos']; ?>
        <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-videos']) ?>
    <?php endif; ?>
<?php endif; ?>