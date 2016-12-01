<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use Yii;
?>

<?php
$attributes = $collection->getEntity()->getValues();

//var_dump($attributes['add_references']->getData(null, $currentLang));exit;
$this->title = $attributes['title']->getData('title');
$this->params['breadcrumbs'][] = ['label' => Html::encode('articles'), 'url' => Url::to(['articles'])];
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

$this->registerJsFile('https://unpkg.com/leaflet@1.0.1/dist/leaflet.js');
$this->registerJsFile('/js/icon.label.js');
$this->registerJsFile('/js/map.js', ['depends' => ['yii\web\YiiAsset']]);
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
                    'type' => (is_array($reference->data_type)) ? implode('<br>', $reference->data_type) : $reference->data_type,
                    'method' => (is_array($reference->method)) ? implode('<br>', $reference->method) : $reference->method
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
        //'json_path_countires' => json_decode(file_get_contents('http://iza.loc/json/economytypes.json'));
        'json_path_country' => '/json/countrydata.json',
        'json_path_economytypes' => '/json/economytypes.json',
        'source' => json_encode($source)
    ];
    
    $this->registerJs("var mapConfig = ".json_encode($config), 3);
?>

<div class="container article-full">

    <div class="breadcrumbs">
        <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
    </div>
    
    <div class="content-inner">
        
        <div class="content-inner-text">
            <div class="map-holder">
                <div class="map-info">
                    <div class="map-info-content"></div>
                    <div class="icon-close"></div>
                </div>
                <div id="map"></div>
            </div>
        </div>
        
        <aside class="sidebar-right">
            
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
            
            <?php if (isset($attributes['related'])): ?>
            <div class="">
                <?php $related = $article->getRelatedArticles($attributes['related']->getData()); ?>
                <a href="" class="title">Related Articles</a>
                <div class="text">
                    <div class="">
                        <ul class="">
                            <?php foreach ($related as $relate): ?>
                            <li>
                                <a href="<?= Url::to('/articles/'.$relate['seo']) ?>"><h3><?= $relate['title'] ?></h3></a>
                                <div class="writer"><?= $relate['availability'] ?></div>
                            </li>
                            <?php endforeach; unset($related); ?>
                        </ul>
                        <a href="" class="more-link">More</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </aside>
    </div>
</div>