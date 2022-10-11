<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use frontend\models\AdvancedSearchForm;
use common\modules\eav\helper\EavAttributeHelper;
use frontend\assets\ArticleAsset;
?>

<?php
$attributes = $collection->getEntity()->getValues();
EavAttributeHelper::initEavAttributes($attributes);

$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.EavAttributeHelper::getAttribute('title')->getData('title', $currentLang);
$this->params['breadcrumbs'][] = ['label' => Html::encode('articles'), 'url' => Url::to(['/articles'])];
$this->params['breadcrumbs'][] = EavAttributeHelper::getAttribute('title')->getData('title', $currentLang);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode(
        implode(' ', 
            array_map(
                function($item) {
                    return $item->word;
                }, EavAttributeHelper::getAttribute('keywords')->getData(null, $currentLang)
            )
        )
    )
]);

$this->registerLinkTag([
    'rel' => 'canonical',
    'href' => Url::to('/articles/'.$article->seo . '/long', true)
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode(EavAttributeHelper::getAttribute('teaser')->getData('teaser', $currentLang))
]);

$this->registerMetaTag([
    'name' => 'citation_journal_title',
    'content' => Html::encode('IZA World of Labor')
]);
$this->registerMetaTag([
    'name' => 'citation_doi',
    'content' => Html::encode($article->doi)
]);
$this->registerMetaTag([
    'name' => 'citation_title',
    'content' => Html::encode(EavAttributeHelper::getAttribute('title')->getData('title', $currentLang))
]);
$this->registerMetaTag([
    'name' => 'citation_publication_date',
    'content' => Html::encode(date('Y-m-d', $article->created_at))
]);
$this->registerMetaTag([
    'name' => 'citation_author',
    'content' => implode(' ', 
        array_map(
            function($author) {
                return $author['name']->last_name.', '.$author['name']->first_name.' '.$author['name']->middle_name;
            }, $authors
        )
    )
]);          
$this->registerMetaTag([
    'name' => 'citation_pdf_url',
    'content' => Url::to(EavAttributeHelper::getAttribute('full_pdf')->getData('url', $currentLang), true)
]);

$authorsList = [];

foreach ($authors as $author) {
    
    $authorsList[] = [
        'name' => $author['name']->first_name.' '.$author['name']->middle_name.' '.$author['name']->last_name,
        'url' => $author['profile']
    ];
}

$mailArticleShare = Yii::$app->view->renderFile('@app/views/emails/articleShare.php', [
    'authorsList' => $authorsList,
    'articleTitle' => EavAttributeHelper::getAttribute('title')->getData('title', $currentLang),
    'articleUrl' => Url::to('/articles/'.$article->seo, true),
    'articleDoi' => $article->doi
]);

$mailArticle = Yii::$app->view->renderFile('@app/views/emails/articleMailto.php',
array(
        'authorsList' => $authorsList,
        'articleTitle' => EavAttributeHelper::getAttribute('title')->getData('title', $currentLang),
        'articleUrl' => Url::to('/articles/'.$article->seo, true),
        'articleDoi' => $article->doi,
        'articleElevatorPitch' => EavAttributeHelper::getAttribute('abstract')->getData('abstract', $currentLang)
));

ArticleAsset::register($this);
    
$config = [
        'json_path' => '/json/countries.geo.json',
        'json_path_country' => '/json/countrydata.json',
        'json_path_economytypes' => '/json/economytypes.json',
        'share_text_for_email' => $mailArticle
];
$affiliationArticle = EavAttributeHelper::getAttribute('affiliation_article')->getData('affiliation');
?>

<div class="container article-full one-pager-page">
    <div class="mobile-filter-holder custom-tabs-holder">
        <ul class="mobile-filter-list">
            <li><a href="/key-topics">Subject areas</a></li>
            <li><a href="/authors">Authors</a></li>
        </ul>
        <div class="mobile-filter-items custom-tabs">
            <div class="tab-item blue js-tab-hidden expand-more"></div>
            <div class="tab-item blue js-tab-hidden expand-more"></div>
        </div>
    </div>

<div class="article-buttons-mobile hide-desktop">

    <ul class="article-buttons-list">
        <li>
            <?php if (isset($attributes['one_pager_pdf'])): ?>
                <a href="<?= Url::to([$attributes['one_pager_pdf']->getData('url', $currentLang), 'v'=>$article->version_number]) ?>" target="_blank" class="btn-border-blue-middle btn-download with-icon-r">
                    <span class="icon-download"></span>
                </a>
            <?php endif; ?>
        </li>
        <li>
            <a href="" class="btn-border-blue-middle btn-cite with-icon-r">
                <span class="icon-quote"></span>
            </a>
        </li>
    </ul>

    <ul class="article-buttons-list">
        <li class="add-fav-holder">
            <div class="add-fav-alert"></div>
            <a href="<?= Url::to(['/article/like', 'id'=>$article['article']->id]) ?>" class="btn-border-gray-middle btn-like short">
                <span class="icon-heart"></span>
            </a>
        </li>
        <li>
            <a target="_blank" href="<?= $mailArticle ?>" class="btn-border-gray-middle short">
                <span class="icon-message"></span>
            </a>
        </li>
        <li>
            <a href="" class="btn-border-gray-middle short btn-print"><span class="icon-print"></span></a>
        </li>
    </ul>
</div>

<div class="article-head">
    <div class="breadcrumbs">
        <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
    </div>

    <div class="article-top">
        <h1><?= EavAttributeHelper::getAttribute('title')->getData('title', $currentLang) ?></h1>
        <h3><?= EavAttributeHelper::getAttribute('teaser')->getData('teaser', $currentLang) ?></h3>
    </div>

    <div class="article-user-list">
        <?php foreach ($authors as $author): ?>
            <div class="article-user">
                <div class="img-holder img-holder-bg">
                    <a href="<?= $author['profile'] ?>" class="img" style="background-image: url(<?= $author['avatar'] ?>)"></a>
                </div>

                <div class="desc">
                    <div class="name">
                        <?= Html::a($author['name']->first_name.' '.
                            $author['name']->middle_name.' '.
                            $author['name']->last_name
                            ,$author['profile']
                        );
                        ?>
                    </div>
                    <p><?= $affiliationArticle[$author['author_key']] ?? $author['affiliation'] ?></p>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

</div>

<div class="content-inner">
    <div class="content-inner-text">
        <article>
            <div class="article-pagers-holder">
                <?php if ($collection->isMulti): ?>
                <div class="language-pagers">
                    <?php if (!$currentLang): ?>
                        <?php foreach($langs as $lang): ?>
                        <a href="<?= Url::toRoute('/articles/'.$article->seo.'/lang/'.$lang['code']) ?>" class="btn-border-gray-middle color-blue">
                            <div class="inner">
                                <span class="text"><?= $lang['name'] ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a href="<?= Url::to('/articles/'.$article->seo) ?>" class="btn-border-gray-middle color-blue">
                            <div class="inner">
                                <span class="text"><?= Yii::$app->params['default_lang']['name'] ?></span>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="article-pagers">
                    <a href="javascript:void(0)" class="active">one-pager</a>
                    <a href="<?= Url::to('/articles/'.$article->seo . '/long') ?>" >full article</a>
                </div>
            </div>

            <h2><?= Yii::t('app/article', 'Elevator pitch') ?></h2>
            <p><?= EavAttributeHelper::getAttribute('abstract')->getData('abstract', $currentLang) ?></p>
            <?php
            $gaImage = EavAttributeHelper::getAttribute('ga_image');
            ?>
            <figure>
                <?php if ($gaImage->getData('target', $currentLang)): ?>
                    <a href="<?= $gaImage->getData('target', $currentLang) ?>" class="text-reference">
                        <img id="<?= $gaImage->getData('id', $currentLang); ?>" data-target="<?= $gaImage->getData('target', $currentLang) ?>" src="<?= $gaImage->getData('path', $currentLang) ?>" alt="<?= $gaImage->getData('title', $currentLang) ?>">
                    </a>
                <?php else: ?>
                    <img id="<?= $gaImage->getData('id', $currentLang); ?>" data-target="<?= $gaImage->getData('target', $currentLang) ?>" src="<?= $gaImage->getData('path', $currentLang) ?>" alt="<?= $gaImage->getData('title', $currentLang) ?>">
                <?php endif; ?>
            </figure>

            <h2><?= Yii::t('app/article', 'Key findings') ?></h2>
            <div class="article-columns">
                <div class="clumn">
                    <h3><?= Yii::t('app/article', 'Pros') ?></h3>
                    <?php foreach (EavAttributeHelper::getAttribute('findings_positive')->getData(null, $currentLang) as $finding): ?>
                        <p><?= $finding->item ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="clumn">
                    <h3><?= Yii::t('app/article', 'Cons') ?></h3>
                    <?php foreach (EavAttributeHelper::getAttribute('findings_negative')->getData(null, $currentLang) as $finding): ?>
                        <p><?= $finding->item ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="authors-main-message">
                <h2><?= Yii::t('app/article', 'Author\'s main message') ?></h2>
                <?= EavAttributeHelper::getAttribute('main_message')->getData('text', $currentLang) ?>
            </div>

            <div class="article-buttons-bottom">
                <div class="share-buttons">
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
                </div>
                <div class="extra-buttons">
                    <ul class="article-buttons-list">
                        <li class="show-one-pager-holder">
                            <a href="<?= Url::to('/articles/'.$article->seo . '/long') ?>" class="btn-border-light-blue-middle btn-show-one-pager">show full article</a>
                        </li>
                        <li>
                            <?php if (isset($attributes['one_pager_pdf'])): ?>
                                <a href="<?= Url::to([$attributes['one_pager_pdf']->getData('url',$currentLang), 'v'=>$article->version_number]) ?>" target="_blank" class="btn-border-blue-middle btn-download with-icon-r">
                                    <div class="inner">
                                        <span class="icon-download"></span>
                                        <span class="text">download pdf</span>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </li>
                        <li>
                            <a href="" class="btn-border-blue-middle btn-cite with-icon-r">
                                <div class="inner">
                                    <span class="icon-quote"></span>
                                    <span class="text">cite</span>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <ul class="article-buttons-list">
                        <li class="add-fav-holder">
                            <div class="add-fav-alert"></div>
                            <a href="<?= Url::to(['/article/like', 'id'=>$article['article']->id]) ?>" class="btn-border-gray-middle btn-like short">
                                <span class="icon-heart"></span>
                            </a>
                        </li>
                        <li>
                            <a href="" class="btn-border-gray-middle btn-print short"><span class="icon-print"></span></a>
                        </li>
                        <li>
                            <a target="_blank" href="<?= $mailArticle ?>" class="btn-border-gray-middle short">
                                <span class="icon-message"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </article>
    </div>
    <aside class="sidebar-right">

        <div class="article-buttons-sidebar hide-mobile">
            <ul class="article-buttons-list">
                <li><?php if (isset($attributes['one_pager_pdf'])): ?>
                    <a href="<?= Url::to([$attributes['one_pager_pdf']->getData('url', $currentLang), 'v'=>$article->version_number]) ?>" target="_blank" class="btn-border-blue-middle btn-download with-icon">
                        <div class="inner">
                            <span class="icon-download"></span>
                            <span class="text">download pdf</span>
                        </div>
                    </a>
                <?php endif; ?>
                </li>
                <li>
                    <a href="" class="btn-border-blue-middle btn-cite with-icon">
                        <div class="inner">
                            <span class="icon-quote"></span>
                            <span>cite</span>
                        </div>
                    </a>
                </li>
                <li class="add-fav-holder">
                    <div class="add-fav-alert"></div>
                    <a href="<?= Url::to(['/article/like', 'id'=>$article['article']->id]) ?>" class="btn-border-gray-middle btn-like short">
                        <span class="icon-heart"></span>
                    </a>
                </li>
                <li>
                    <a href="" class="btn-border-gray-middle short btn-print"><span class="icon-print"></span></a>
                </li>
                <li>
                    <a target="_blank" href="<?= $mailArticle ?>" class="btn-border-gray-middle short">
                        <span class="icon-message"></span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-widget sidebar-widget-share-buttons">
            <div class="share-buttons">
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
            </div>
        </div>

        <div class="sidebar-widget sidebar-widget-keywords">
            <div class="widget-title">Keywords</div>
            <?=
            implode(', ', array_map(
                function($item) {
                    return Html::a($item->word, '#', ['class' => 'search-keywords-article']);
                }, EavAttributeHelper::getAttribute('keywords')->getData(null, $currentLang)
            ));
            ?>
            <?php $model = new AdvancedSearchForm(); ?>
            <?php $form = ActiveForm::begin(['action'=>'/search', 'options' => ['class' => 'keywords-search-form', 'style' => 'display:none']]); ?>
                <?= $form->field($model, 'search_phrase') ?>
            <?php ActiveForm::end(); ?>
        </div>

        <?php
        $count_categories = count($categories);
        $previos_lvl = 0;
        ?>

        <?php if ($count_categories > 0): ?>
            <div class="sidebar-widget">
                <div class="widget-title">Classification</div>
                <ul class="classification-list">
                    <li>
                    <?php foreach ($categories as $c): ?>
                        <?php if ($c['lvl'] == 1): ?>
                            <?php if ($previos_lvl > 1): ?>
                                <?= str_repeat ('</li></ul>', $previos_lvl-1); ?>
                                </li><li>
                            <?php endif; ?>
                            <a href="<?= Url::to([$c['url_key']]) ?>"><?= $c['title'] ?></a>
                        <?php else: ?>
                            <?php if ($previos_lvl == $c['lvl']): ?>
                                </li><li><a href="<?= Url::to([$c['url_key']]) ?>"><?= $c['title'] ?></a>
                            <?php else: ?>
                                <ul><li><a href="<?= Url::to([$c['url_key']]) ?>"><?= $c['title'] ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php $previos_lvl = $c['lvl']; ?>
                    <?php endforeach; ?>
                    </li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="sidebar-widget sidebar-widget-evidence-map">
            <a href="<?= Url::to('/articles/'.$article['article']->seo . '/map') ?>">
                <div id="map-mini"></div>
                <div class="caption">
                    <div class="title">Evidence map</div>
                    <span class="icon-arrow-square-blue">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </span>
                </div>
            </a>
        </div>

        <div class="sidebar-widget sidebar-widget-articles-references">
            <ul class="sidebar-accrodion-list">

                <?php if (isset($attributes['related'])): ?>
                    <?php $related = $article['article']->getRelatedArticles($attributes['related']->getData(null, $currentLang)); ?>
                    <?php $count_related = count($related) ?>

                    <?php if ($count_related > 0): ?>
                        <li class="sidebar-accrodion-item sidebar-articles-item is-open">
                        <a href="" class="title">Related Articles</a>
                        <div class="text">
                            <ul class="sidebar-news-list">
                                <?php foreach ($related as $relate): ?>
                                    <li>
                                        <h3><a href="<?= Url::to('/articles/'.$relate['seo']) ?>"><?= $relate['title'] ?></a></h3>
                                        <div class="writers">
                                            <?php foreach($relate['authors'] as $author): ?>
                                                <span class="writer-item"><?= Html::a($author['name'], $author['url']) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </li>
                                <?php endforeach; unset($related); ?>
                            </ul>
                            <?php if($count_related > 5): ?>
                                <a href="" class="more-link">
                                    <span class="more">More</span>
                                    <span class="less">Less</span>
                                </a>
                            <?php endif ?>
                        </div>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (isset($attributes['further_reading'])): ?>
                    <?php $furthers = $attributes['further_reading']->getData(null, $currentLang); ?>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Further reading</a>
                        <div class="text">
                            <ul class="further-reading-list">
                                <?php foreach ($furthers as $further): ?>
                                    <li>
                                        <a href=""><?= $further->title ?></a>
                                        <div class="icon-exclamatory-circle rel-tooltip"></div>
                                        <div class="further-reading-info">
                                            <?= $further->full_citation ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if(count($furthers) > 13): ?>
                                <a href="" class="more-link">
                                    <span class="more">More</span>
                                    <span class="less">Less</span>
                                </a>
                            <?php endif ?>
                        </div>
                    </li>
                <?php endif; ?>

                <?php $source = []; ?>

                <?php if (isset($attributes['key_references'])): ?>
                    <?php $references = $attributes['key_references']->getData(null, $currentLang); ?>
                    <?php if(count($references) > 0): ?>
                        <li class="sidebar-accrodion-item key-references-item">
                            <a href="" class="title">Key references</a>
                            <div class="text">
                                <?php $i = 1; ?>
                                <ul class="key-references-list">
                                    <?php foreach($references as $reference): ?>
                                        <?php if (count($reference->country_codes)): ?>
                                            <?php $source = array_merge($source, $reference->country_codes); ?>
                                        <?php endif; ?>
                                        <li>
                                            <a href="#<?= $reference->ref ?>">[<?= $i++ ?>] <?= $reference->title ?></a>
                                            <div class="icon-exclamatory-circle rel-tooltip"></div>
                                            <div class="key-references-info">
                                                <div class="caption"><span class="caption-number">[<?= $i-1 ?>]</span><?= (is_array($reference->full_citation)) ? implode('<br>', $reference->full_citation) : $reference->full_citation?></div>
                                                <div class="sources">
                                                    <?php if(is_array($reference->data_source)): ?>
                                                        <?php
                                                        $s = 1;
                                                        foreach ($reference->data_source as $dSource) {
                                                            echo '<div class="item"><span class="caption-number">['.$s.']</span>'.$dSource.'</div>';
                                                            $s++;
                                                        }
                                                        ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="types">
                                                    <?php if(is_array($reference->data_type)): ?>
                                                        <?php
                                                        $s = 1;
                                                        foreach ($reference->data_type as $dataType) {
                                                            echo '<div class="item"><span class="hide-desktop caption-number">['.$s.']</span>'.$dataType.'</div>';
                                                            $s++;
                                                        }
                                                        ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="methods"><?= (is_array($reference->method)) ? implode(' - ', $reference->method) : $reference->method ?></div>
                                                <div class="countries"><?= (is_array($reference->countries)) ? implode(', ', $reference->countries) : '' ?></div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if(count($references) > 13): ?>
                                    <a href="" class="more-link">
                                        <span class="more">More</span>
                                        <span class="less">Less</span>
                                    </a>
                                <?php endif ?>
                            </div>
                        </li>
                    <?php endif; ?>

                <?php endif; ?>

                <?php $source = []; ?>

                <?php if (isset($attributes['add_references'])): ?>
                    <?php $additionals = $attributes['add_references']->getData(null, $currentLang); ?>
                    <?php $i = 1; ?>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Additional References</a>
                        <div class="text">
                            <ul class="additional-references-list">
                                <?php foreach($additionals as $additional): ?>
                                    <?php if (count($additional->country_codes)): ?>
                                        <?php $source = array_merge($source, $additional->country_codes); ?>
                                    <?php endif; ?>
                                    <li>
                                        <a href=""><?= $additional->title ?></a>
                                        <?php $i++; ?>
                                        <div class="icon-exclamatory-circle rel-tooltip"></div>
                                        <div class="additional-references-info">
                                            <?= $additional->full_citation ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if(count($additionals) > 13): ?>
                                <a href="" class="more-link">
                                    <span class="more">More</span>
                                    <span class="less">Less</span>
                                </a>
                            <?php endif ?>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="sidebar-widget sidebar-widget-reference-popup dropdown">
            <div class="reference-popup-list-holder drop-content">
                <div class="reference-popup-list">
                    <div class="icon-close"></div>
                    <div class="reference-popup-list-inner">
                        <?php if (isset($attributes['further_reading'])): ?>
                            <?php $furthers = $attributes['further_reading']->getData(null, $currentLang); ?>
                            <h3>Further reading</h3>
                            <ul class="further-reading-popup-list">
                                <?php foreach ($furthers as $further): ?>
                                    <li>
                                        <?= $further->full_citation ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (isset($attributes['key_references'])): ?>
                            <?php $references = $attributes['key_references']->getData(null, $currentLang); ?>
                            <h3>Key references</h3>
                            <?php $i = 1; ?>
                            <?php if(count($references) > 0): ?>
                            <ul class="key-references-popup-list">
                                <?php foreach($references as $reference): ?>
                                    <?php if (count($reference->country_codes)): ?>
                                        <?php $source = array_merge($source, $reference->country_codes); ?>
                                    <?php endif; ?>
                                    <li>
                                        <?= (is_array($reference->full_citation)) ? implode('<br>', $reference->full_citation) : $reference->full_citation?>
                                        <div class="key-reference-in-popup">Key reference: <a href="#<?= $reference->ref ?>">[<?= $i++ ?>]</a></div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (isset($attributes['add_references'])): ?>
                            <?php $additionals = $attributes['add_references']->getData(null, $currentLang); ?>
                            <h3>Additional References</h3>
                            <?php if(count($additionals) > 0): ?>
                            <ul class="additional-references-popup-list">
                                <?php foreach($additionals as $additional): ?>
                                    <li>
                                        <?= $additional->full_citation ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="widget-title">Full reference list</div>
            <a href="" class="dropdown-link">Open full reference list</a>
        </div>

        <div class="sidebar-widget">
            <a href="<?= Url::to('/articles/'.$article->seo . '/references#print') ?>" class="btn-border-blue-middle with-icon" target="_blank">
                <div class="inner">
                    <span class="icon-print"></span><span>print all references</span>
                </div>
            </a>
        </div>
        
        <?= $this->renderFile(__DIR__.'/sidebar/versions.php', ['article' => $article, 'authorsList'=>$authorsList]); ?>
    </aside>
</div>
</div>

<div class="reference-popup">
    <div class="reference-popup-inner">
        <div class="container">
            <div class="container-inner">
                <div class="cite-input-box-holder">
                    <div class="cite-input-box">
                        <textarea cols="15" rows="10" class="form-control" id="copy-field"></textarea>
                        <button class="btn-blue download-cite-button">Download</button>
                        <button class="btn-blue copy-cite-button">Copy</button>
                    </div>
                </div>
                <div class="column-bg-info">
                    <div class="bg-info"></div>
                </div>
                <div class="column-additional-references">
                    <div class="additional-references"></div>
                </div>
                <div class="column-furniture-reading">
                    <h3>Full citation</h3>
                    <div class="furniture-reading"></div>
                </div>
                <div class="column-caption">
                    <h3>Full citation</h3>
                    <div class="caption">
                        <a href=""></a>
                    </div>
                </div>
                <div class="columns-holder">
                    <div class="columns">
                        <div class="column column-sources">
                            <h3>Data source(s)</h3>
                            <div class="sources"></div>
                        </div>
                        <div class="column column-types">
                            <h3>Data type(s)</h3>
                            <div class="types"></div>
                        </div>
                    </div>
                </div>
                <div class="column-methods">
                    <h3>Method(s)</h3>
                    <div class="methods"></div>
                </div>
                <div class="column-countries">
                    <h3>Countries</h3>
                    <div class="countries"></div>
                </div>
            </div>
        </div>
        <div class="arrows">
            <div class="icon-circle-arrow left">
                <span class="icon-icon-arrow-square-blue-left">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </span>
            </div>
            <div class="icon-circle-arrow right">
                <span class="icon-arrow-square-blue">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="icon-close-popup"></div>
</div>

<?php

$cite = [
    'authors' => $authors,
    'title' => EavAttributeHelper::getAttribute('title')->getData('title', $currentLang),
    'publisher' => 'IZA World of Labor',
    'date' => date('Y', $article->created_at),
    'id' => $article['article']->id,
    'doi' => $article->doi,
    'postUrl' => '/article/download-cite'
];

$config['source'] = array_unique($source);
$this->registerJs("var mapConfig = ".json_encode($config), 3);
$this->registerJs("var citeConfig = ".json_encode($cite), 3);
?>