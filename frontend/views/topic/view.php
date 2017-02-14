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

$mailMap = Yii::$app->view->renderFile('@app/views/emails/defMailto.php', [
    'articleTitle' => $model->title,
    'articleUrl' => Url::to('/key-topics/'.$model['url_key'], true),
    'typeContent' => 'key topic'
]);
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
            <?php Pjax::begin(['linkSelector' => '#load-articles', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['class' => 'loader-ajax']]); ?>
            <ul class="post-list topic-articles-list">
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
            <?php Pjax::begin(['linkSelector' => '#load-opinions', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['class' => 'loader-ajax']]); ?>
            <ul class="post-list media-list topic-opinion-list">
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
            <?php Pjax::begin(['linkSelector' => '#load-videos', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['class' => 'loader-ajax']]); ?>
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
            <?php Pjax::begin(['linkSelector' => '#load-events', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['class' => 'loader-ajax']]); ?>
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

        <aside class="sidebar-right hide-mobile">
            <div class="sidebar-buttons-holder">
                <ul class="share-buttons-list">
                    <li class="share-facebook">
                        <!-- Sharingbutton Facebook -->
                        <a class="resp-sharing-button__link facebook-content" href="" target="_blank" aria-label="Facebook">
                            <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg></div>Facebook</div>
                        </a>
                    </li>
                    <li class="share-twitter">
                        <!-- Sharingbutton Twitter -->
                        <a class="resp-sharing-button__link twitter-content" href="" target="_blank" aria-label="Twitter">
                            <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg></div>Twitter</div>
                        </a>
                    </li>
                    <li class="share-ln">
                        <!-- Sharingbutton LinkedIn -->
                        <a class="resp-sharing-button__link linkedin-content" href="" target="_blank" aria-label="LinkedIn">
                            <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg></div>LinkedIn</div>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-email-holder">
                    <a target="_blank" href="<?= $mailMap ?>" class="btn-border-gray-small with-icon-r">
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

                <?php if (count($keyTopics) > Yii::$app->params['key_topics_sidebar_limit']): ?>
                <a href="" class="more-link">
                    <span class="more">More</span>
                    <span class="less">Less</span>
                </a>
                <?php endif; ?>
                <?php //Html::a('More', ['topic/index'], ['class' => 'more-link no-open']); ?> 
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>