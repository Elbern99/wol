<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
//use Yii;
?>

<?php
$attributes = $collection->getEntity()->getValues();
//var_dump($attributes['creation']->getData('main_creation'));exit;
$this->title = $attributes['title']->getData('title');
$this->params['breadcrumbs'][] = ['label' => Html::encode('articles'), 'url' => Url::to(['/articles'])];

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode(
        implode(' ', 
            array_map(
                function($item) {
                    return $item->word;
                }, $attributes['keywords']->getData()
            )
        )
    )
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($attributes['teaser']->getData('teaser'))
]);

$this->registerJsFile('/js/plugins/share-text.js', ['depends'=>['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/scrollend.js', ['depends'=>['yii\web\YiiAsset']]);
$this->registerJsFile('/js/pages/article.js', ['depends'=>['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/leaflet.js');
$this->registerJsFile('/js/plugins/share-buttons.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile('/css/leaflet.css');

$config = [
        'json_path' => '/json/countries.geo.json',
        'json_path_country' => '/json/countrydata.json',
        'json_path_economytypes' => '/json/economytypes.json'
];

?>
<div class="container article-full">
    <div class="article-buttons article-buttons-mobile">
        <?php if (isset($attributes['full_pdf'])): ?>
        <a href="<?= $attributes['full_pdf']->getData('url') ?>" target="_blank" class="btn-border-blue-middle btn-download with-icon-r">
            <span class="icon-download"></span>
        </a>
        <?php endif; ?>
        <a href="" class="btn-border-blue-middle btn-cite with-icon-r">
            <span class="icon-quote"></span>
        </a>
        <a href="mailto:?subject=<?= urlencode('Article from IZA World of Labor') ?>
                    &body=<?= urlencode('Title:') ?>
                    <?= urlencode($attributes['title']->getData('title')) ?>
                    <?= urlencode($attributes['teaser']->getData('teaser')) ?>
                    <?= urlencode(Url::to(['/articles/'.$article->seo],true)) ?>
                    <?= urlencode('Elevator pitch:') ?>
                    <?= urlencode($attributes['abstract']->getData('abstract')) ?>
                    <?= urlencode('View the article') ?>
                    <?= urlencode(Url::to(['/articles/'.$article->seo],true)) ?>
                    <?= urlencode('Copyright © IZA') ?>
                    <?= date('Y') ?> <?= urlencode('Impressum. All Rights Reserved. ISSN: 2054-9571') ?>" class="btn-border-gray-middle short">
            <span class="icon-message"></span>
        </a>
        <a href="" class="btn-border-gray-middle short btn-print"><span class="icon-print"></span></a>
        <a href="" class="btn-border-gray-middle btn-like short">
            <span class="icon-heart"></span>
            <div class="btn-like-inner">article added to favorites</div>
        </a>
    </div>

    <div class="article-head">
        <div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
        </div>

        <div class="article-top">
            <h1><?= $attributes['title']->getData('title') ?></h1>
            <h3><?= $attributes['teaser']->getData('teaser') ?></h3>
        </div>

        <?php foreach ($authors as $author): ?>
            <div class="article-user">
                <div class="img"><a href=""><img src="<?= $author['avatar'] ?>" alt=""></a></div>

                <?php $authorAttributes = $author['collection']->getEntity()->getValues(); ?>

                <div class="desc">
                    <div class="name">
                        <?php

                        $link = Html::a($authorAttributes['name']->getData('first_name').' '.
                            $authorAttributes['name']->getData('middle_name').' '.
                            $authorAttributes['name']->getData('last_name')
                            ,'/'
                        );

                        $authorLink[] = $link;
                        echo $link;
                        ?>
                    </div>
                    <p><?= $authorAttributes['affiliation']->getData('affiliation') ?></p>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <article>
                <div class="article-pagers-holder">
                    <div class="article-pagers">
                        <a href="<?= Url::to('/articles/'.$article->seo) ?>">one-pager</a>
                        <a href="javascript:void(0)" class="active" >full article</a>
                    </div>
                </div>

                <h2>Elevator pitch</h2>
                <p><?= $attributes['abstract']->getData('abstract') ?></p>

                <figure>
                    <img id="<?= $attributes['ga_image']->getData('id'); ?>" data-target="<?= $attributes['ga_image']->getData('target') ?>" src="<?= $attributes['ga_image']->getData('path') ?>" alt="<?= $attributes['ga_image']->getData('title') ?>" width="430" height="326">
                </figure>

                <h2>Key findings</h2>
                <div class="article-columns">
                    <div class="clumn">
                        <h3>Pros</h3>
                        <?php foreach ($attributes['findings_positive']->getData() as $finding): ?>
                            <p><?= $finding->item ?></p>
                        <?php endforeach; ?>
                    </div>
                    <div class="clumn">
                        <h3>Cons</h3>
                        <?php foreach ($attributes['findings_negative']->getData() as $finding): ?>
                            <p><?= $finding->item ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>

                <h2>Author's main message</h2>
                <p><?= $attributes['main_message']->getData('text') ?></p>
                
                <?php foreach ($attributes['main_text']->getData() as $main): ?>
                    <h2><?= \Yii::t('app/text',$main->type) ?></h2>
                    <p><?= $main->text ?></p>
                <?php endforeach; ?>
                    
                <h2>Competing interests</h2>
                <p><?= $attributes['creation']->getData('main_creation') ?></p>
                <p><a href="">&copy; <?=$article->availability?></a></p>

                <div class="article-map-medium">
                    <a href="<?= Url::to('/articles/'.$article->seo.'/map') ?>">
                        <div class="article-map-medium-text">
                            <h4>evidence map</h4>
                            <p>Can cash transfers reduce child labor?</p>
                            <div class="icon-circle-arrow">
                                <div class="icon-arrow"></div>
                            </div>
                        </div>
                        <div id="article-map-medium"></div>
                    </a>
                </div>
                
                <div class="article-buttons">
                    <div class="share-buttons">
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
                    </div>
                    <div class="extra-buttons">
                        <a href="<?= Url::to('/articles/'.$article->seo) ?>" class="btn-border-blue-middle btn-show-one-pager"><span class="text">show one-pager</span></a>
                        <?php if (isset($attributes['full_pdf'])): ?>
                        <a href="<?= $attributes['full_pdf']->getData('url') ?>" target="_blank" class="btn-border-blue-middle btn-download with-icon-r">
                            <div class="inner">
                                <span class="icon-download"></span>
                                <span class="text">download pdf</span>
                            </div>
                        </a>
                        <?php endif; ?>
                        <a href="" class="btn-border-blue-middle btn-cite with-icon-r">
                            <div class="inner">
                                <span class="icon-quote"></span>
                                <span class="text">cite</span>
                            </div>
                        </a>
                        <div class="article-buttons-short">
                            <a href="" class="btn-border-gray-middle btn-like short">
                                <span class="icon-heart"></span>
                                <div class="btn-like-inner">article added to favorites</div>
                            </a>
                            <a href="" class="btn-border-gray-middle btn-print short"><span class="icon-print"></span></a>
                            <a href="mailto:?subject=<?= urlencode('Article from IZA World of Labor') ?>
                                &body=<?= urlencode('Title:') ?>
                                <?= urlencode($attributes['title']->getData('title')) ?>
                                <?= urlencode($attributes['teaser']->getData('teaser')) ?>
                                <?= urlencode(Url::to(['/articles/'.$article->seo],true)) ?>
                                <?= urlencode('Elevator pitch:') ?>
                                <?= urlencode($attributes['abstract']->getData('abstract')) ?>
                                <?= urlencode('View the article') ?>
                                <?= urlencode(Url::to(['/articles/'.$article->seo],true)) ?>
                                <?= urlencode('Copyright © IZA') ?>
                                <?= date('Y') ?> <?= urlencode('Impressum. All Rights Reserved. ISSN: 2054-9571') ?>" class="btn-border-gray-middle short">
                                <span class="icon-message"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <aside class="sidebar-right">
            <div class="article-buttons article-buttons-sidebar">
                <?php if (isset($attributes['full_pdf'])): ?>
                    <a href="<?= $attributes['full_pdf']->getData('url') ?>" target="_blank" class="btn-border-blue-middle btn-download with-icon-r">
                        <span class="icon-download"></span>
                        <span class="text">download pdf</span>
                    </a>
                <?php endif; ?>
                <a href="" class="btn-border-blue-middle btn-cite with-icon">
                    <span class="inner">
                        <span class="icon-quote"></span>
                        <span>cite</span>
                    </span>
                </a>
                <a href="" class="btn-border-gray-middle btn-like short">
                    <span class="icon-heart"></span>
                    <div class="btn-like-inner">article added to favorites</div>
                </a>
                <a href="" class="btn-border-gray-middle short btn-print"><span class="icon-print"></span></a>
                <a href="mailto:?subject=<?= urlencode('Article from IZA World of Labor') ?>
                    &body=<?= urlencode('Title:') ?>
                    <?= urlencode($attributes['title']->getData('title')) ?>
                    <?= urlencode($attributes['teaser']->getData('teaser')) ?>
                    <?= urlencode(Url::to(['/articles/'.$article->seo],true)) ?>
                    <?= urlencode('Elevator pitch:') ?>
                    <?= urlencode($attributes['abstract']->getData('abstract')) ?>
                    <?= urlencode('View the article') ?>
                    <?= urlencode(Url::to(['/articles/'.$article->seo],true)) ?>
                    <?= urlencode('Copyright © IZA') ?>
                    <?= date('Y') ?> <?= urlencode('Impressum. All Rights Reserved. ISSN: 2054-9571') ?>" class="btn-border-gray-middle short">
                    <span class="icon-message"></span>
                </a>
            </div>

            <div class="sidebar-widget sidebar-widget-share-buttons">
                <div class="share-buttons">
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
                </div>
            </div>

            <div class="sidebar-widget">
                <div class="widget-title">Keywords</div>
                <?=
                implode(', ', array_map(
                    function($item) {
                        return Html::a($item->word);
                    }, $attributes['keywords']->getData()
                ));
                ?>
            </div>

            
            <?php
                $count_categories = count($categories);
            ?>

            <?php if ($count_categories > 0): ?>
            <div class="sidebar-widget">
                <div class="widget-title">Classification</div>
                <ul class="classification-list">
                    <?php foreach ($categories as $c): ?>
                        <li>
                            <?php if (isset($c['p_id'])): ?>
                                <a href="<?= Url::to([$c['p_url_key']]) ?>"><?= $c['p_title'] ?></a>&nbsp;>&nbsp;
                            <?php endif; ?>
                            <a href="<?= Url::to([$c['url_key']]) ?>"><?= $c['title'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="sidebar-widget sidebar-widget-evidence-map">
                <a href="<?= Url::to('/articles/'.$article->seo . '/map') ?>">
                    <div id="map-mini"></div>
                    <div class="caption">
                        <div class="title">Evidence map</div>
                        <div class="icon-next-circle"></div>
                    </div>
                </a>
            </div>
            
            <div class="sidebar-widget sidebar-widget-articles-references">
                <ul class="sidebar-accrodion-list hide-desktop">

                    <?php if (isset($attributes['term_groups'])): ?>
                        <li class="sidebar-accrodion-item">
                            <?php $backgrounds = $attributes['term_groups']->getData(); ?>
                            <a href="" class="title">Background information</a>
                            <div class="text">
                                <div class="text-inner">
                                    <ul class="sidebar-news-list bg-news-list">
                                        <?php foreach($backgrounds as $key=>$value): ?>
                                            <li>
                                                <a href="#<?=$key?>"><?= $value->title ?></a>
                                                <div class="icon-exclamatory-circle rel-tooltip"></div>
                                                <div class="bg-info">
                                                    <h3><?= $value->title ?></h3>
                                                    <?= $value->text ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php if(count($backgrounds) > 13): ?>
                                        <a href="" class="more-link">
                                            <span class="more">More</span>
                                            <span class="less">Less</span>
                                        </a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($attributes['related'])): ?>
                        <li class="sidebar-accrodion-item sidebar-articles-item">
                            <?php $related = $article->getRelatedArticles($attributes['related']->getData()); ?>
                            <?php $count_related = count($related) ?>

                            <?php if ($count_related > 0): ?>
                                <a href="" class="title">Related Articles</a>
                                <div class="text">
                                    <ul class="sidebar-news-list">
                                        <?php foreach ($related as $relate): ?>
                                            <li>
                                                <h3><a href="<?= Url::to('/articles/'.$relate['seo']) ?>"><?= $relate['title'] ?></a></h3>
                                                <div class="writer"><?= $relate['availability'] ?></div>
                                            </li>
                                        <?php endforeach; unset($related); ?>
                                    </ul>
                                    <?php if(count($count_related) > 13): ?>
                                        <a href="" class="more-link">
                                            <span class="more">More</span>
                                            <span class="less">Less</span>
                                        </a>
                                    <?php endif ?>
                                </div>
                            <?php endif; ?>
                        </li>

                    <?php endif; ?>
            
                    <?php if (isset($attributes['further_reading'])): ?>
                    <?php $furthers = $attributes['further_reading']->getData(); ?>
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
                    <?php $references = $attributes['key_references']->getData(); ?>
                    <li class="sidebar-accrodion-item">
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
                                        <div class="caption"><?= (is_array($reference->full_citation)) ? implode('<br>', $reference->full_citation) : $reference->full_citation?></div>
                                        <div class="sources"><?= (is_array($reference->data_source)) ? implode('<br>', $reference->data_source) : $reference->data_source ?></div>
                                        <div class="types"><?=  (is_array($reference->data_type)) ? implode('<br>', $reference->data_type) : $reference->data_type ?></div>
                                        <div class="methods"><?= (is_array($reference->method)) ? implode('<br>', $reference->method) : $reference->method ?></div>
                                        <div class="countries"><?= (is_array($reference->countries)) ? implode('<br>', $reference->countries) : $reference->countries ?></div>
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

                    <?php if (isset($attributes['add_references'])): ?>
                    <?php $additionals = $attributes['add_references']->getData(); ?>
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
                <a href="<?= Url::to('/articles/'.$article->seo . '/references#print') ?>" class="btn-border-blue-middle with-icon" target="_blank">
                    <div class="inner">
                        <span class="icon-print"></span><span>print all references</span>
                    </div>
                </a>
            </div>
            
            <div class="sidebar-widget sidebar-widget-reference-popup dropdown">
                <div class="reference-popup-list-holder drop-content">
                    <div class="reference-popup-list">
                        <div class="icon-close"></div>
                        <div class="reference-popup-list-inner">
                            <?php if (isset($attributes['further_reading'])): ?>
                                <?php $furthers = $attributes['further_reading']->getData(); ?>
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
                                <?php $references = $attributes['key_references']->getData(); ?>
                                <h3>Key references</h3>
                                <?php $i = 1; ?>
                                <ul class="key-references-popup-list">
                                    <?php foreach($references as $reference): ?>
                                        <li>
                                            <?= (is_array($reference->full_citation)) ? implode('<br>', $reference->full_citation) : $reference->full_citation?>
                                            <div class="key-reference-in-popup">Key reference: <a href="#<?= $reference->ref ?>">[<?= $i++ ?>]</a></div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (isset($attributes['add_references'])): ?>
                                <?php $additionals = $attributes['add_references']->getData(); ?>
                                <h3>Additional References</h3>
                                <ul class="additional-references-popup-list">
                                    <?php foreach($additionals as $additional): ?>
                                        <li>
                                            <?= $additional->full_citation ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="widget-title">Full reference list</div>
                <a href="" class="dropdown-link">Open full reference list</a>
            </div>
            
            <div class="sidebar-widget sidebar-widget-version">
                <div class="sidebar-widget-version-item">
                    <div class="widget-title">Versions</div>
                    <!--<div class="number">
                        <div class="icon-question tooltip">
                            <div class="tooltip-content">
                                This is a revision
                            </div>
                        </div>
                        <a href="">current version: <strong>2</strong></a>
                    </div>-->
                    <div class="date">
                        <div class="title">date</div>
                        <?= date('F Y', $article->created_at) ?>
                    </div>
                    <div class="doi">
                        <div class="title">DOI</div>
                        <a href=""><?= $article->doi ?></a>
                    </div>
                    <div class="authors">
                        <div class="title">authors</div>
                        <a href=""><?= $article->availability ?></a>
                    </div>
                    <div class="article-number">Article number: <strong><?= $article->id ?></strong></div>
                </div>
            </div>
        </aside>
    </div>
</div>

<div class="reference-popup">
    <div class="reference-popup-inner">
        <div class="container">
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
            <div class="columns">
                <div class="column column-sources">
                    <h3>Data source(s)</h3>
                    <div class="sources"></div>
                </div>
                <div class="column column-types">
                    <h3>Data type(s)</h3>
                    <div class="types"></div>
                </div>
                <div class="column column-methods">
                    <h3>Method(s)</h3>
                    <div class="methods"></div>
                </div>
            </div>
            <div class="column-countries">
                <h3>Countries</h3>
                <div class="countries"></div>
            </div>
        </div>
        <div class="arrows">
            <div class="icon-circle-arrow left">
                <div class="icon-arrow"></div>
            </div>
            <div class="icon-circle-arrow right">
                <div class="icon-arrow"></div>
            </div>
        </div>
    </div>
    <div class="icon-close-popup"></div>
</div>
<?php
$config['source'] = array_unique($source);
$this->registerJs("var mapConfig = ".json_encode($config), 3);
?>