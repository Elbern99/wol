<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<ul class="post-list">
    <?php foreach($collection as $article): ?>
    <li class="post-item">
        <ul class="article-rubrics-list">
            <?php foreach($article['category'] as $link): ?>
                <li><?= $link ?></li>
            <?php endforeach; ?>
        </ul>
        <h2><a href="<?= $article['url'] ?>"><?= $article['title'] ?></a></h2>
        <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
        <div class="publish"><a href=""><?= $article['availability']  ?></a></div>
        <div class="description">
            <?= $article['abstract']->abstract ?? ''; ?>
        </div>
        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
    </li>
    <?php endforeach; ?>
</ul>
