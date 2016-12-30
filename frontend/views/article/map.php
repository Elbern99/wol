<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
//use Yii;
?>

<?php
$attributes = $collection->getEntity()->getValues();

//var_dump($attributes['add_references']->getData(null, $currentLang));exit;
$this->title = $attributes['title']->getData('title');
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

$this->registerJsFile('/js/plugins/share-text.js', ['depends'=>['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/leaflet.js');
$this->registerJsFile('/js/plugins/icon.label.js');
$this->registerJsFile('/js/pages/map.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/share-buttons.js', ['depends' => ['yii\web\YiiAsset']]);
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

$mailBody = 'Hi.\n\n I think that you would be interested in the  following article from IZA World of labor. \n\n  Title: '.$attributes['title']->getData('title').
    '\n\n View the article: '. Url::to(['/articles/'.$article->seo],true). '/map'. '\n\n Copyright © IZA 2016'.'Impressum. All Rights Reserved. ISSN: 2054-9571';
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
                <p>The colored countries below have empirical evidence for this topic. The color indicates the country's development status, based on the country classification shown in the legend, and the number on the flag indicates how many relevant academic studies address this policy question. If you click on the flag, an overlay pops up that shows the key and additional references for an article.</p>
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
                    <a href="mailto:?subject=<?= Html::encode('Article from IZA World of Labor') ?>&body=<?= Html::encode($mailBody) ?>" class="btn-border-gray-small with-icon-r">
                        <div class="inner">
                            <span class="icon-message"></span>
                            <span class="text">email</span>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="sidebar-widget">
                <div class="widget-title">Article</div>
                <a href="<?= Url::to('/articles/'.$article->seo) ?>"><?= $this->title ?></a>
                <?= $article->availability ?>
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

            <?php if (isset($attributes['related'])): ?>
                <?php $related = $article->getRelatedArticles($attributes['related']->getData()); ?>
                <?php $count_related = count($related) ?>
                    <?php if ($count_related > 0): ?>
                    <div class="sidebar-widget">
                        <div class="widget-title">Related Articles</div>
                        <ul class="sidebar-news-list">
                            <?php foreach ($related as $relate): ?>
                                <li>
                                    <h3><a href="<?= Url::to('/articles/' . $relate['seo']) ?>"><?= $relate['title'] ?></a></h3>
                                    <div class="writer"><?= $relate['availability'] ?></div>
                                </li>
                            <?php endforeach;
                            unset($related); ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <?php
                        if($count_related > 10) {
                            echo '<a href="" class="more-link">More</a>';
                        }
                    ?>
            <?php endif; ?>
        </aside>
    </div>
</div>