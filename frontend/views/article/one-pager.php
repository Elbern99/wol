<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use Yii;
?>

<?php
$attributes = $collection->getEntity()->getValues();
//var_dump();exit;
$this->title = $attributes['title']->getData('title');
$this->params['breadcrumbs'][] = Html::encode('articles');

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
?>
<div class="container article-full">

    <div class="breadcrumbs">
        <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
    </div>

    <div class="article-top">
        <h1><?= $attributes['title']->getData('title') ?></h1>
        <h3><?= $attributes['teaser']->getData('teaser') ?></h3>
    </div>

    <div class="article-user">
        <div class="img"><a href=""><img src="images/temp/editors/img-01.jpg" alt=""></a></div>
        <div class="desc">
            <div class="name"><a href=""><?= $article->availability ?></a></div>
            <p>University of Rome Tor Vergata, ICID, and Understanding Children’s Work, Italy, and IZA, Germany</p>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <article>
                <div class="article-pagers">
                    <a href="javascript:void(0)" class="active">one-pager</a>
                    <a href="<?= Url::to(Yii::$app->request->url . '/long') ?>" >full article</a>
                </div>

                <h2>Elevator pitch</h2>
                <p><?= $attributes['abstract']->getData('abstract') ?></p>

                <figure>
                    <img src="<?= $attributes['ga_image']->getData('path') ?>" alt="<?= $attributes['ga_image']->getData('title') ?>" width="430" height="326">
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
                <?= $attributes['main_message']->getData('text') ?>
            </article>
        </div>
        <aside class="sidebar-right">
            <div class="article-buttons article-buttons-sidebar">
                <a href="" class="btn-border-blue-middle"><span>show one-pager</span></a>
                <a href="" class="btn-border-blue-middle cite"><span class="icon-quote"></span><span>cite</span></a>
                <a href="" class="btn-border-gray-middle short"><span class="icon-heart"></span></a>
                <a href="" class="btn-border-gray-middle short "><span class="icon-print"></span></a>
                <a href="" class="btn-border-gray-middle short"><span class="icon-message"></span></a>
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

            <div class="sidebar-widget">
                <div class="widget-title">Classification</div>
                <ul class="classification-list">
                    <li><a href="">Labor markets and institutions</a></li>
                    <li><a href="">Transition and emerging economies > Gender issues</a></li>
                    <li><a href="">Demography, family, and gender > Family</a></li>
                    <li><a href="">Et harum quidem rerum facilis est et expedita distinctio > Itaque earum rerum hic tenetur a sapiente delectus</a></li>
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
            
            <ul class="sidebar-accrodion-list">
                
                <?php if (isset($attributes['term_groups'])): ?>
                <li class="sidebar-accrodion-item is-open">
                    <?php $backgrounds = $attributes['term_groups']->getData(); ?>
                    <a href="" class="title">Background information</a>
                    <div class="text">
                        <div class="text-inner">
                            <ul class="sidebar-news-list">
                                <?php foreach($backgrounds as $key=>$value): ?> 
                                <li>
                                    <a href="#<?=$key?>"><h3><?= $value->title ?></h3></a>
                                    <div class="icon-question"></div>
                                    <?= $value->text ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </li>
                <?php endif; ?>
                
                <?php if (isset($attributes['related'])): ?>
                <li class="sidebar-accrodion-item">
                    <?php $related = $article->getRelatedArticles($attributes['related']->getData()); ?>
                    <a href="" class="title">Related Articles</a>
                    <div class="text">
                        <div class="text-inner">
                            <ul class="sidebar-news-list">
                                <?php foreach ($related as $relate): ?>
                                <li>
                                    <a href="<?= Url::to('/articles/'.$relate['seo']) ?>"><h3>Social protection programs for women in developing countries</h3></a>
                                    <div class="writer"><?= $relate['availability'] ?></div>
                                </li>
                                <?php endforeach; unset($related); ?>
                            </ul>
                            <a href="" class="more-link">More</a>
                        </div>
                    </div>
                </li>
                <?php endif; ?>
                
                <?php if (isset($attributes['further_reading'])): ?>
                <?php $furthers = $attributes['further_reading']->getData(); ?>
                <li class="sidebar-accrodion-item">
                    <a href="" class="title">Further reading</a>
                    <div class="text">
                        <div class="text-inner">
                            <ul class="further-reading-list">
                                <?php foreach ($furthers as $further): ?>
                                <li>
                                    <h3><?= $further->title ?></h3>
                                    <div class="icon-question"></div>
                                    <?= $further->full_citation ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </li>
                <?php endif; ?>
                
                <?php if (isset($attributes['key_references'])): ?>
                <?php $references = $attributes['key_references']->getData(); ?>
                <li class="sidebar-accrodion-item">
                    <a href="" class="title">Key references</a>
                    <div class="text">
                        <div class="text-inner">
                            <?php $i = 1; ?>
                            <ul class="key-references-list">
                                <?php foreach($references as $reference): ?>
                                <li><a href="#<?= $reference->ref ?>">[<?= $i++ ?>]<?= $reference->title ?></a></li>
                                <?= (is_array($reference->full_citation)) ? implode('<br>', $reference->full_citation) : $reference->full_citation?>
                                <?= (is_array($reference->data_source)) ? implode('<br>', $reference->data_source) : $reference->data_source ?>
                                <?php /* (is_array($reference->data_type)) ? implode('<br>', $reference->data_type) : $reference->data_type*/ ?>
                                <?= (is_array($reference->method)) ? implode('<br>', $reference->method) : $reference->method ?>
                                <?= (is_array($reference->countries)) ? implode('<br>', $reference->countries) : $reference->countries ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </li>
                <?php endif; ?>
                
                <?php if (isset($attributes['add_references'])): ?>
                <?php $additionals = $attributes['add_references']->getData(); ?>
                <li class="sidebar-accrodion-item">
                    <a href="" class="title">Additional References</a>
                    <div class="text">
                        <div class="text-inner">
                            <ul class="additional-references-list">
                                <?php foreach($additionals as $additional): ?> 
                                <li><?= $additional->title ?></li>
                                <?= $additional->full_citation ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
            
            <div>
                <h3>Version</h3>
                <p><?= date('F Y', $article->created_at) ?></p>
                <p><?= $article->doi ?></p>
                <p><?= $article->availability ?></p>
                <p><?= $article->id ?></p>
            </div>
        </aside>
    </div>
</div>