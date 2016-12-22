<?php use yii\helpers\Url; ?>
<div class="tab js-tab-hidden" id="tab-2">

    <ul class="favourite-articles-list">
        <?php foreach ($articlesCollection as $article): ?>
        <li class="article-item">
            <a href="<?= Url::to(['/my-account/remove-favorite', 'id' => $article['fovorit_id']]) ?>">
                <div class="icon-close"></div>
            </a>
            <ul class="article-rubrics-list">
                <?php foreach ($article['category'] as $link): ?>
                    <li><?= $link ?></li>
                <?php endforeach; ?>
            </ul>
            <h2><a href="<?= $article['url'] ?>"><?= $article['title'] ?></a></h2>
            <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
            <div class="publish"><a href=""><?= $article['availability'] ?></a>, <?= date('F Y', $article['created_at']) ?></div>
            <div class="description">
                <?= $article['abstract']->abstract ?? ''; ?>
            </div>
            <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>