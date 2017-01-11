<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<?php
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
 $this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => Html::encode('Key Topics'), 'url' => Url::to(['/topic/index'])];
$this->params['breadcrumbs'][] = $model->title;

$this->registerJsFile('/js/plugins/share-buttons.js', ['depends' => ['yii\web\YiiAsset']]);
?>

<?php if ($model->image_link) : ?>
<div class="header-background" style="background-image: url('/uploads/topics/<?= $model->image_link; ?>');"></div>
<?php endif; ?>

<div class="container key-topics-page">

    <div class="article-head">
        <div class="breadcrumbs">
           <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
        </div>
        <?php if ($model->is_key_topic) : ?>
        <div class="extra-title">key topic</div>
        <?php endif; ?>
        <h1><?= $model->title; ?></h1>
    </div>

    <div class="content-inner">
        <div class="content-inner-text contact-page">
            <p>
                <?= $model->description; ?>
            </p>
            <?php if ($collection) : ?>
            <div class="widget-title medium"><?= Html::a('articles', ['article/index']); ?></div>
            <?php Pjax::begin(['linkSelector' => '#load-articles', 'enableReplaceState' => false, 'enablePushState' => false]); ?>
            <ul class="post-list">
                <?php foreach($collection as $article): ?>
                <li class="post-item">
                    <ul class="article-rubrics-list">
                        <?php foreach($article['category'] as $link): ?>
                            <li><?= $link ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <h2><a href="<?= $article['url'] ?>"><?= $article['title'] ?></a></h2>
                    <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
                    <div class="publish"><a href=""><?= $article['availability']  ?></a></div>
                    <div class="description">
                        <?= $article['abstract']->abstract ?? ''; ?>
                    </div>
                    <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php if ($relatedArticlesCount > Yii::$app->params['topic_articles_limit']): ?>
                    <?php
                    if ($sort == 3) {
                        $params = ['/topic/articles', 'article_limit' => Yii::$app->params['topic_articles_limit'], 'topic_id' => $model->id];
                    } else {
                        $params = ['/topic/articles', 'article_limit' => Yii::$app->params['topic_articles_limit'], 'sort' => 1, 'topic_id' => $model->id];
                    }
                    ?>
                    <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray align-center', 'id' => 'load-articles']) ?>
            <?php else: ?>
                <?php if (Yii::$app->request->get('article_limit')): ?>
                    <?php
                    if ($sort == 3) {
                        $params = ['/topic/articles', 'topic_id' => $model->id];
                    } else {
                        $params = ['/topic/articles', 'sort' => 1, 'topic_id' => $model->id];
                    }
                    ?>
                    <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray align-center', 'id' => 'load-articles']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php Pjax::end(); ?>
            <?php endif; ?>
            
            <?php if ($relatedOpinions) : ?>
            <div class="widget-title medium"><?= Html::a('opinions', ['opinion/index']); ?></div>
            <?php Pjax::begin(['linkSelector' => '#load-opinions', 'enableReplaceState' => false, 'enablePushState' => false]); ?>
            <ul class="post-list media-list">
                <?php foreach ($relatedOpinions as $item) : ?>
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
                    <div class="publish"><?= $opinion->getAuthorsLink(); ?></div>
                    <h2>
                        <?= Html::a($opinion->title, ['opinion/view', 'slug' => $opinion->url_key]); ?>
                    </h2>
                </li>
                <?php endforeach; ?>
               
            </ul>
            <?php if ($relatedOpinionsCount > Yii::$app->params['topic_opinions_limit']): ?>
            <?php $params = ['/topic/opinions', 'opinion_limit' => Yii::$app->params['topic_opinions_limit'], 'topic_id' => $model->id]; ?>
            <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-opinions']) ?>
            <?php else: ?>
                <?php if (Yii::$app->request->get('opinion_limit')): ?>
                     <?php $params = ['/topic/opinions', 'topic_id' => $model->id]; ?>
                    <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-opinions']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php Pjax::end(); ?>
            <?php endif; ?>
            
            <?php if ($relatedVideos) : ?>
            <div class="widget-title medium"><?= Html::a('videos', ['video/index']); ?></div>
            <?php Pjax::begin(['linkSelector' => '#load-videos', 'enableReplaceState' => false, 'enablePushState' => false]); ?>
            <ul class="post-list media-list">
                <?php foreach ($relatedVideos as $item) : ?>
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
            
            <?php if ($relatedVideosCount > Yii::$app->params['topic_videos_limit']): ?>
            <?php $params = ['/topic/videos', 'video_limit' => Yii::$app->params['topic_videos_limit'], 'topic_id' => $model->id]; ?>
            <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-videos']) ?>
            <?php else: ?>
                <?php if (Yii::$app->request->get('video_limit')): ?>
                     <?php $params = ['/topic/videos', 'topic_id' => $model->id]; ?>
                    <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-videos']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php Pjax::end(); ?>
            <?php endif; ?>
            <?php if ($relatedEvents) : ?>
            <div class="widget-title medium"><?= Html::a('events', ['event/index']); ?></div>
            <?php Pjax::begin(['linkSelector' => '#load-events', 'enableReplaceState' => false, 'enablePushState' => false]); ?>
            <ul class="post-list media-list">
                <?php foreach ($relatedEvents as $item) : ?>
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
            
            <?php if ($relatedEventsCount > Yii::$app->params['topic_events_limit']): ?>
            <?php $params = ['/topic/events', 'event_limit' => Yii::$app->params['topic_events_limit'], 'topic_id' => $model->id]; ?>
            <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-events']) ?>
            <?php else: ?>
                <?php if (Yii::$app->request->get('event_limit')): ?>
                     <?php $params = ['/topic/events', 'topic_id' => $model->id]; ?>
                    <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray show-more align-center', 'id' => 'load-events']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php Pjax::end(); ?>
            <?php endif; ?>

        </div>

        <aside class="sidebar-right">
            <div class="sidebar-buttons-holder">
                <ul class="share-buttons-list">
                    <li class="share-facebook">
                        <div id="fb-root"></div>
                        <div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div>
                    </li>
                    <li class="share-twitter">
                        <a class="twitter-share-button" href="https://twitter.com/intent/tweet">Tweet</a>
                    </li>
                    <li class="share-ln">
                        <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                        <script type="IN/Share"></script>
                    </li>
                </ul>

                <div class="sidebar-email-holder">
                    <a href="" class="btn-border-gray-small with-icon-r">
                        <div class="inner">
                            <span class="icon-message"></span>
                            <span class="text">email</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sidebar-widget expand-more">
                <div class="widget-title">key topics</div>
                <?php if ($keyTopics) : ?>
                <ul class="sidebar-key-topics-list">
                    <?php foreach ($keyTopics as $topic) : ?>
                    <?php if ($topic->url_key == $model->url_key) : ?>
                    <li class="active">
                        <span><?= $topic->title; ?></span>
                    <?php else : ?>
                    <li>
                    <?= Html::a($topic->title, ['topic/view', 'slug' => $topic->url_key]); ?>
                    <?php endif; ?>
                  
                    </li>
                    <?php endforeach; ?>
                </ul>
                
<!--                <a href="" class="more-link">
                    <span class="more">More</span>
                    <span class="less">Less</span>
                </a>-->
                <?= Html::a('More', ['topic/index'], ['class' => 'more-link no-open']); ?> 
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>