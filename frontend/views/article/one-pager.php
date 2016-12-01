<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use Yii;
?>

<?php
$attributes = $collection->getEntity()->getValues();
$currentLang = null;

if ($collection->isMulti) {
    $currentLang = 0;
}
//var_dump($attributes['add_references']->getData(null, $currentLang));exit;
$this->title = $attributes['title']->getData('title', $currentLang);
$this->params['breadcrumbs'][] = ['label' => Html::encode('articles'), 'url' => Url::to(['articles'])];

$this->registerMetaTag([
        'name' => 'keywords',
        'content' => Html::encode(
                implode(' ',
                        array_map(
                                function($item) {
                                    return $item->word;
                                }, $attributes['keywords']->getData(null ,$currentLang)
                        )
                )
        )
]);

$this->registerMetaTag([
        'name' => 'description',
        'content' => Html::encode($attributes['teaser']->getData('teaser', $currentLang))
]);

$authorLink = [];

$this->registerJsFile('/js/article.js', ['depends'=>['yii\web\YiiAsset']]);
?>
<div class="container article-full">

    <div class="breadcrumbs">
        <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
    </div>

    <div class="article-top">
        <h1><?= $attributes['title']->getData('title', $currentLang) ?></h1>
        <h3><?= $attributes['teaser']->getData('teaser', $currentLang) ?></h3>
    </div>

    <?php foreach ($authors as $author): ?>
    <div class="article-user">
        <div class="img"><a href=""><img src="<?= $author['avatar'] ?>" alt=""></a></div>

        <?php $authorAttributes = $author['collection']->getEntity()->getValues(); ?>

        <div class="desc">
            <div class="name"
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

<div class="content-inner">
    <div class="content-inner-text">
        <article>
            <div class="article-pagers-holder">
                <div class="language-pagers">
                    <a href="" class="btn-border-gray-middle with-icon-r">
                        <div class="lang"><img src="images/lang/germany.jpg" alt=""></div>
                        <span class="text">in Deutsch lesen</span>
                    </a>
                </div>
                <div class="article-pagers">
                    <a href="javascript:void(0)" class="active">one-pager</a>
                    <a href="<?= Url::to(Yii::$app->request->url . '/long') ?>" >full article</a>
                </div>
            </div>

            <h2>Elevator pitch</h2>
            <p><?= $attributes['abstract']->getData('abstract', $currentLang) ?></p>

            <figure>
                <img id="<?php /*$attributes['ga_image']->getData('id', $currentLang)*/ ?>" data-target="<?= $attributes['ga_image']->getData('target', $currentLang) ?>" src="<?= $attributes['ga_image']->getData('path', $currentLang) ?>" alt="<?= $attributes['ga_image']->getData('title', $currentLang) ?>" width="430" height="326">
            </figure>

            <h2>Key findings</h2>
            <div class="article-columns">
                <div class="clumn">
                    <h3>Pros</h3>
                    <?php foreach ($attributes['findings_positive']->getData(null, $currentLang) as $finding): ?>
                        <p><?= $finding->item ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="clumn">
                    <h3>Cons</h3>
                    <?php foreach ($attributes['findings_negative']->getData(null, $currentLang) as $finding): ?>
                        <p><?= $finding->item ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <h2>Author's main message</h2>
            <?= $attributes['main_message']->getData('text', $currentLang) ?>
        </article>
    </div>
    <aside class="sidebar-right">
        <div class="article-buttons article-buttons-sidebar">
            <a href="" class="btn-border-blue-middle btn-download with-icon"><span class="icon-download"></span><span class="text">download pdf</span></a>
            <a href="" class="btn-border-blue-middle btn-cite"><span class="icon-quote"></span><span>cite</span></a>
            <a href="" class="btn-border-gray-middle btn-like short">
                <span class="icon-heart"></span>
                <div class="btn-like-inner">article added to favorites</div>
            </a>
            <a href="" class="btn-border-gray-middle short btn-print"><span class="icon-print"></span></a>
            <a href="" class="btn-border-gray-middle short"><span class="icon-message"></span></a>
        </div>

        <div class="sidebar-widget">
            <div class="widget-title">Keywords</div>
            <?=
            implode(', ', array_map(
                    function($item) {
                        return Html::a($item->word);
                    }, $attributes['keywords']->getData(null, $currentLang)
            ));
            ?>
        </div>

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

        <div class="sidebar-widget sidebar-widget-evidence-map">
            <a href="">
                <div class="caption">
                    <div class="title">Evidence map</div>
                    <div class="icon-circle-arrow white">
                        <div class="icon-arrow"></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="sidebar-widget sidebar-widget-articles-references">
            <ul class="sidebar-accrodion-list">

                <?php if (isset($attributes['term_groups'])): ?>
                    <li class="sidebar-accrodion-item">
                        <?php $backgrounds = $attributes['term_groups']->getData(null, $currentLang); ?>
                        <a href="" class="title">Background information</a>
                        <div class="text">
                            <div class="text-inner">
                                <ul class="sidebar-news-list">
                                    <?php foreach($backgrounds as $key=>$value): ?>
                                        <li>
                                            <h3><a href="#<?=$key?>"><?= $value->title ?></a></h3>
                                            <div class="icon-question rel-tooltip"></div>
                                            <div class="bg-info"><?= $value->text ?></div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php
                                if(count($backgrounds) > 10) {
                                    echo  '<a href="" class="more-link">More</a> ';
                                }
                                ?>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if (isset($attributes['related'])): ?>
                    <li class="sidebar-accrodion-item">
                        <?php $related = $article->getRelatedArticles($attributes['related']->getData(null, $currentLang)); ?>
                        <a href="" class="title">Related Articles</a>
                        <div class="text">
                            <div class="text-inner">
                                <ul class="sidebar-news-list">
                                    <?php foreach ($related as $relate): ?>
                                        <li>
                                            <h3><a href="<?= Url::to('/articles/'.$relate['seo']) ?>"><?= $relate['title'] ?></a></h3>
                                            <div class="writer"><?= $relate['availability'] ?></div>
                                        </li>
                                    <?php endforeach; unset($related); ?>
                                </ul>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if (isset($attributes['further_reading'])): ?>
                    <?php $furthers = $attributes['further_reading']->getData(null, $currentLang); ?>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Further reading</a>
                        <div class="text">
                            <div class="text-inner">
                                <ul class="further-reading-list">
                                    <?php foreach ($furthers as $further): ?>
                                        <li>
                                            <h3><?= $further->title ?></h3>
                                            <div class="icon-question rel-tooltip"></div>
                                            <div class="further-reading-info">
                                                <?= $further->full_citation ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php
                                if(count($furthers) > 10) {
                                    echo  '<a href="" class="more-link">More</a> ';
                                }
                                ?>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if (isset($attributes['key_references'])): ?>
                    <?php $references = $attributes['key_references']->getData(null, $currentLang); ?>
                    <li class="sidebar-accrodion-item">
                    <a href="" class="title">Key references</a>
                    <div class="text">
                        <?php $i = 1; ?>
                        <ul class="key-references-list">
                            <?php foreach($references as $reference): ?>
                                <li>
                                    <a href="#<?= $reference->ref ?>">[<?= $i++ ?>] <?= $reference->title ?></a>
                                    <div class="icon-question rel-tooltip"></div>
                                    <div class="key-references-info">
                                        <div class="caption"><?= (is_array($reference->full_citation)) ? implode('<br>', $reference->full_citation) : $reference->full_citation?></div>
                                        <div class="sources"><?= (is_array($reference->data_source)) ? implode('<br>', $reference->data_source) : $reference->data_source ?></div>
                                        <div class="types"><?php /* (is_array($reference->data_type)) ? implode('<br>', $reference->data_type) : $reference->data_type*/ ?></div>
                                        <div class="methods"><?= (is_array($reference->method)) ? implode('<br>', $reference->method) : $reference->method ?></div>
                                        <div class="countries"><?= (is_array($reference->countries)) ? implode('<br>', $reference->countries) : $reference->countries ?></div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php
                            if(count($references) > 10) {
                                echo  '<a href="" class="more-link">More</a> ';
                            }
                        ?>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if (isset($attributes['add_references'])): ?>
                    <?php $additionals = $attributes['add_references']->getData(null, $currentLang); ?>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Additional References</a>
                        <div class="text">
                            <div class="text-inner">
                                <ul class="additional-references-list">
                                    <?php foreach($additionals as $additional): ?>
                                        <li>
                                            <?= $additional->title ?>
                                            <?php $i++; ?>
                                            <div class="icon-question rel-tooltip"></div>
                                            <div class="additional-references-info">
                                                <?= $additional->full_citation ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php
                                if(count($additionals) > 10) {
                                    echo  '<a href="" class="more-link">More</a> ';
                                }
                                ?>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div>
            <h3>Version</h3>
            <p><?= date('F Y', $article->created_at) ?></p>
            <p><?= $article->doi ?></p>
            <p><?= implode('<br>', $authorLink) ?></p>
            <p><?= $article->id ?></p>
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
        <div class="icon-close-popup"></div>
        <div class="arrows">
            <div class="icon-circle-arrow left">
                <div class="icon-arrow"></div>
            </div>
            <div class="icon-circle-arrow right">
                <div class="icon-arrow"></div>
            </div>
        </div>
    </div>
</div>