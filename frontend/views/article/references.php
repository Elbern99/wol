<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
$attributes = $collection->getEntity()->getValues();
//var_dump($attributes['creation']->getData('main_creation'));exit;
$this->title = $attributes['title']->getData('title');
$this->params['breadcrumbs'][] = ['label' => Html::encode('articles'), 'url' => Url::to(['/articles'])];

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode(
            implode(' ', array_map(
                            function($item) {
                                return $item->word;
                            }, $attributes['keywords']->getData()
                    )
            )
    )
]);
?>
<div class="container article-full">

    <div class="breadcrumbs">
        <?= $this->renderFile('@app/views/components/breadcrumbs.php'); ?>
    </div>

    <div class="article-top">
        <h1><?= $attributes['title']->getData('title') ?></h1>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <ul>
                <?php if (isset($attributes['further_reading'])): ?>
                    <?php $furthers = $attributes['further_reading']->getData(); ?>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Further reading</a>
                        <div class="text">
                            <ul class="further-reading-list">
                                <?php foreach ($furthers as $further): ?>
                                    <li>
                                        <a href=""><?= $further->title ?></a>
                                        <div class="icon-question rel-tooltip"></div>
                                        <div class="further-reading-info">
                                            <?= $further->full_citation ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
                <?php if (isset($attributes['key_references'])): ?>
                    <?php $references = $attributes['key_references']->getData(); ?>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Key references</a>
                        <div class="text">
                            <?php $i = 1; ?>
                            <ul class="key-references-list">
                                <?php foreach ($references as $reference): ?>
                                    <li>
                                        <a href="#<?= $reference->ref ?>">[<?= $i++ ?>] <?= $reference->title ?></a>
                                        <div class="icon-question rel-tooltip"></div>
                                        <div class="key-references-info">
                                            <div class="caption"><?= (is_array($reference->full_citation)) ? implode('<br>', $reference->full_citation) : $reference->full_citation ?></div>
                                            <div class="sources"><?= (is_array($reference->data_source)) ? implode('<br>', $reference->data_source) : $reference->data_source ?></div>
                                            <div class="types"><?= (is_array($reference->data_type)) ? implode('<br>', $reference->data_type) : $reference->data_type ?></div>
                                            <div class="methods"><?= (is_array($reference->method)) ? implode('<br>', $reference->method) : $reference->method ?></div>
                                            <div class="countries"><?= (is_array($reference->countries)) ? implode('<br>', $reference->countries) : $reference->countries ?></div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if (isset($attributes['add_references'])): ?>
                    <?php $additionals = $attributes['add_references']->getData(); ?>
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">Additional References</a>
                        <div class="text">
                            <ul class="additional-references-list">
                                <?php foreach ($additionals as $additional): ?>
                                    <li>
                                        <a href=""><?= $additional->title ?></a>
                                        <div class="icon-question rel-tooltip"></div>
                                        <div class="additional-references-info">
                                            <?= $additional->full_citation ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>       
    </div>
</div>
