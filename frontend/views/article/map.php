<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
//use Yii;
?>

<?php
$attributes = $collection->getEntity()->getValues();

$this->title = 'Evidence map for '.$attributes['title']->getData('title');
$this->params['breadcrumbs'][] = ['label' => Html::encode('articles'), 'url' => Url::to(['/articles'])];
$this->params['breadcrumbs'][] = 'Evidence map';

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

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($attributes['teaser']->getData('teaser'))
]);

$this->registerJsFile('/js/plugins/share-text.js', ['depends'=>['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/leaflet.js');
$this->registerJsFile('/js/plugins/icon.label.js');
$this->registerJsFile('/js/pages/map.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile('/css/leaflet.css');
?>

<?php
    $source = [];
    
    if (isset($attributes['key_references'])) {
        
        $references = $attributes['key_references']->getData();
        $i = 1;
        foreach($references as $reference) {
            
            if (!empty($reference->country_codes)) {
                
                $data = [
                    'position' => $i,
                    'title' => $reference->title,
                    'full_citation' => (is_array($reference->full_citation)) ? implode('<br>', $reference->full_citation) : $reference->full_citation,
                    'source' => (is_array($reference->data_source)) ? implode('<br>', $reference->data_source) : $reference->data_source,
                    'type' => (is_array($reference->data_type)) ? implode(', ', $reference->data_type) : $reference->data_type,
                    'method' => (is_array($reference->method)) ? implode(', ', $reference->method) : $reference->method
                ];
                
                foreach ($reference->country_codes as $code) {
                    
                    if (isset($source[$code])) {
                        $source[$code]['key_references'][] = $data;
                        continue;
                    }
                    
                    $source[$code] = ['key_references' => array($data)];
                }
            }
            
            $i++;
        }
    }
    
    if (isset($attributes['add_references'])) {

        $additionals = $attributes['add_references']->getData();
        
        foreach($additionals as $additional) {
            
            if (!empty($additional->country_codes)) {

                $data = [
                    'title' => $additional->title,
                    'full_citation' => (is_array($additional->full_citation)) ? implode('<br>', $additional->full_citation) : $additional->full_citation,
                ];

                foreach ($additional->country_codes as $code) {

                    if (isset($source[$code])) {
                        $source[$code]['additional_references'][] = $data;
                        continue;
                    }

                    $source[$code] = ['additional_references' => array($data)];
                }
            }
            
        }

    }
    
    $config = [
        'json_path' => '/json/countries.geo.json',
        'json_path_country' => '/json/countrydata.json',
        'json_path_economytypes' => '/json/economytypes.json',
        'source' => json_encode($source)
    ];
    
    $this->registerJs("var mapConfig = ".json_encode($config), 3);

    $mailMap = Yii::$app->view->renderFile('@app/views/emails/defMailto.php', [
        'articleTitle' => $attributes['title']->getData('title'),
        'articleUrl' => Url::to('/articles/'.$article->seo, true),
        'typeContent' => 'article'
    ]);
?>

<div class="container article-map">

    <div class="article-head">
        <div class="breadcrumbs">
            <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
        </div>

        <div class="map-title">Evidence map</div>
        <h1><?= $attributes['title']->getData('title'); ?></h1>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">

            <div class="map-holder">
                <div class="map-info">
                    <div class="map-info-inner">
                        <div class="map-info-content"></div>
                        <div class="icon-close"></div>
                    </div>
                </div>
                <div id="map"></div>
            </div>

            <ul class="evidence-map-list">
                <li><div class="icon-square-dark-green"></div>Innovation-driven economy</li>
                <li><div class="icon-square-green"></div>Efficiency-driven economy in transition to a more advanced stage</li>
                <li><div class="icon-square-lime-green"></div>Efficiency-driven economy</li>
                <li><div class="icon-square-yellow-green"></div>Factor-driven economy in transition to a more advanced stage</li>
                <li><div class="icon-square-yellow"></div>Factor-driven economy</li>
                <li><div class="icon-number-reference">3</div>Number of references</li>
            </ul>

            <div class="evidence-map-text">
                <p>The colored countries above have empirical evidence for this topic. The color indicates the country's development status, based on the country classification shown in the legend, and the number on the flag indicates how many relevant academic studies address this policy question. If you click on the flag, an overlay pops up that shows the key and additional references for an article.</p>
                <a href="" class="more-evidence-map-text-mobile"><span class="more">More</span><span class="less">Less</span></a>
            </div>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget evidence-map-list-holder">
                <ul class="sidebar-accrodion-list ">
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">legend</a>
                        <div class="text">
                            <ul class="evidence-map-list">
                                <li><div class="icon-square-dark-green"></div>Innovation-driven economy</li>
                                <li><div class="icon-square-green"></div>Efficiency-driven economy in transition to a more advanced stage</li>
                                <li><div class="icon-square-lime-green"></div>Efficiency-driven economy</li>
                                <li><div class="icon-square-yellow-green"></div>Factor-driven economy in transition to a more advanced stage</li>
                                <li><div class="icon-square-yellow"></div>Factor-driven economy</li>
                                <li><div class="icon-number-reference">3</div>Number of references</li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            
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
                <div class="widget-title">Article</div>
                <a href="<?= Url::to('/articles/'.$article->seo) ?>"><?= $attributes['title']->getData('title') ?></a>
                <div class="writer">
                    <?php if (count($authors)): ?>
                        <?php foreach($authors as $author): ?>
                            <span class="writer-item"><?= Html::a($author['name'], $author['url']) ?></span>
                        <?php endforeach; ?>
                    <?php endif;?>
                </div>
            </div>

            <div class="sidebar-widget sidebar-widget-keywords">
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

            <?php if (isset($attributes['related'])): ?>
                <?php $related = $article->getRelatedArticles($attributes['related']->getData()); ?>
                <?php $count_related = count($related) ?>
                    <?php if ($count_related > 0): ?>
                    <div class="sidebar-widget expand-more">
                        <div class="widget-title">Related Articles</div>
                        <ul class="sidebar-news-list">
                            <?php foreach ($related as $relate): ?>
                                <li>
                                    <h3><a href="<?= Url::to('/articles/'.$relate['seo']) ?>"><?= $relate['title'] ?></a></h3>
                                    <div class="writer">
                                        <?php foreach($relate['authors'] as $author): ?>
                                            <span class="writer-item"><?= Html::a($author['name'], $author['url']) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </li>
                            <?php endforeach;
                            unset($related); ?>
                        </ul>
                        <?php
                            if($count_related > 5) {
                                echo '<a href="" class="more-link"><span class="more">More</span><span class="less">Less</span></a>';
                            }
                        ?>
                    </div>
                    <?php endif; ?>
            <?php endif; ?>
        </aside>
    </div>
</div>