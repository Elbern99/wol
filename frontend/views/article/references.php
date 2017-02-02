<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
$attributes = $collection->getEntity()->getValues();
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.$attributes['title']->getData('title');
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

    <div class="article-reference-top">
        <div class="line">
            <a href="" class="btn-border-blue-middle with-icon btn-print">
                <div class="inner">
                    <span class="icon-print"></span><span>print</span>
                </div>
            </a>
            <h1>References for <?= $attributes['title']->getData('title') ?></h1>
        </div>
        Author: <a href=""><?= $article->availability ?></a>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <ul class="all-references-list">
                <?php if (isset($attributes['further_reading'])): ?>
                    <?php $furthers = $attributes['further_reading']->getData(); ?>
                    <li>
                        <h2>Further reading</h2>
                        <ul>
                            <?php foreach ($furthers as $further): ?>
                                <li>
                                    <?= $further->full_citation ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (isset($attributes['key_references'])): ?>
                    <?php $references = $attributes['key_references']->getData(); ?>
                    <li>
                        <h2>Key references</h2>
                        <?php $i = 1; ?>
                        <ul>
                            <?php foreach ($references as $reference): ?>
                                <li>
                                    <?= (is_array($reference->full_citation)) ? implode('<br>', $reference->full_citation) : $reference->full_citation ?>
                                    <div class="all-key-reference-count">
                                        Key reference: <a href="#<?= $reference->ref ?>">[<?= $i++ ?>]</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (isset($attributes['add_references'])): ?>
                    <?php $additionals = $attributes['add_references']->getData(); ?>
                    <li>
                        <h2>Additional References</h2>
                        <ul>
                            <?php foreach ($additionals as $additional): ?>
                                <li>
                                    <?= $additional->full_citation ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>       
    </div>
</div>
