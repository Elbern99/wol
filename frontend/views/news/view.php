<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\filters\NewsletterArchiveWidget;
use frontend\components\filters\NewsArchiveWidget;
?>

<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$newsItemDirectLink = Url::to(['/news/view', 'slug' => $model->url_key], true);
$mailLink = $newsItemDirectLink;
$mailTitle = $model->title;
$this->title = Html::encode($prefixTitle.$model->title);

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
$this->params['breadcrumbs'][] = ['label' => Html::encode('News'), 'url' => Url::to(['/news/index'])];
$this->params['breadcrumbs'][] = $model->title;

$mailMap = Yii::$app->view->renderFile('@app/views/emails/defMailto.php', [
    'articleTitle' => $mailTitle,
    'articleUrl' => $mailLink,
    'typeContent' => 'news'
]);

$newsletterArchiveWidget = NewsletterArchiveWidget::widget(['data' => $newsletterArchive]);
$newsArchiveWidget = NewsArchiveWidget::widget(['data' => $newsArchive]);
?>

<div class="container about-page">
	
	<div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
	</div>
        <div class="mobile-filter-holder custom-tabs-holder">
            <ul class="mobile-filter-list">
                <li><a href="" class="js-widget">Latest news</a></li>
                <li><a href="" class="js-widget">News archives</a></li>
                <li><a href="" class="js-widget">Newsletters</a></li>
            </ul>
            <div class="mobile-filter-items custom-tabs">
                <div class="tab-item js-tab-hidden empty">
                     <ul class="sidebar-news-list">
                        <?php foreach ($newsSidebar as $item) : ?>
                        <li>
                            <h3>
                                <?= Html::a($item->title, ['/news/view', 'slug' => $item->url_key]); ?>
                            </h3>
                            <?php if ($item->editor) : ?>
                            <div class="writers">
                                <?= $item->editor; ?>
                            </div>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                        
                    </ul>
                    <?php if (count($newsSidebar) > Yii::$app->params['latest_news_sidebar_limit']): ?>
                    <a href="" class="more-link">
                        <span class="more">More</span>
                        <span class="less">Less</span>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="tab-item blue js-tab-hidden expand-more">
                    <?= $newsArchiveWidget ?>
                </div>
                <div class="tab-item blue js-tab-hidden expand-more">
                    <?= $newsletterArchiveWidget; ?>
                </div>
            </div>
        </div>
	
	<div class="content-inner">
            <div class="content-inner-text contact-page">
                <article class="full-article">
                    <div class="head-news-holder">
                        <div class="head-news">
                            <div class="date">
                                <?= $model->created_at->format('F d, Y'); ?>
                            </div>
                            <?php if ($model->editor) : ?>
                            <div class="publish">
                                <?= $model->editor; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <h1><?= $model->title; ?></h1>
                    <?php $hasImage= $model->image_link ? true : false; ?>
                    <?php if ($hasImage) : ?>
                    <figure class="align-left">
                        <?= Html::img('/uploads/news/'.$model->image_link, [
                            'alt' => $model->title,
                        ]); ?>
                    </figure>
                    <?php endif; ?>

                    <p>
                        <?= $model->description; ?>
                    </p>
                    </article>
		</div>
		
		<aside class="sidebar-right">

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

            <div class="sidebar-widget">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open hide-mobile">
                        <a href="" class="title">Latest news</a>
                        <div class="text">
                            <div class="text-inner">
                                <ul class="sidebar-news-list">
                                    <?php foreach ($newsSidebar as $item) : ?>
                                    <li>
                                        <h3>
                                            <?= Html::a($item->title, ['/news/view', 'slug' => $item->url_key]); ?>
                                        </h3>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if (count($newsSidebar) > Yii::$app->params['latest_news_sidebar_limit']): ?>
                                <a href="" class="more-link">
                                    <span class="more">More</span>
                                    <span class="less">Less</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item hide-mobile">
                        <a href="" class="title">news archives</a>
                        <div class="text">
                            <?= $newsArchiveWidget ?>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item hide-mobile">
                        <a href="" class="title">newsletters</a>
                        <div class="text">
                            <?= $newsletterArchiveWidget; ?>
                        </div>
                    </li>
                    <?php if ($articlesSidebar) : ?>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">related articles</a>
                        <div class="text">
                            <ul class="sidebar-news-list">
                                <?php foreach($articlesSidebar as $article) : ?>
                                <?php $article = $article->article; ?>
                                <li>
                                    <h3>
                                        <?= Html::a($article->title, ['/article/one-pager', 'slug' => $article->seo]); ?>
                                    </h3>
                                    <div class="writers"><?= $article->availability; ?></div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (count($articlesSidebar) > Yii::$app->params['latest_articles_sidebar_limit']): ?>
                            <a href="" class="more-link">
                                <span class="more">More</span>
                                <span class="less">Less</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if (count($widgets)): ?>
                <?php foreach ($widgets as $widget): ?>
                    <?= $widget['text'] ?>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </aside>
	</div>
</div>