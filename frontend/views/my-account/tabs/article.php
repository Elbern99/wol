<?php use yii\helpers\Url; ?>
<div class="tab js-tab-hidden" id="tab-2">
    <?php if (count($articlesCollection)): ?>
    <ul class="post-list favourite-articles-list">
        <?php foreach ($articlesCollection as $article): ?>
        <li class="post-item">
            <a href="<?= Url::to(['/my-account/remove-favorite', 'id' => $article['fovorit_id']]) ?>" class="icon-close"></a>
            <ul class="article-rubrics-list">
                <?php foreach ($article['category'] as $link): ?>
                    <li><?= $link ?></li>
                <?php endforeach; ?>
            </ul>
            <h2><a href="<?= $article['url'] ?>"><?= $article['title'] ?></a></h2>
            <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
            <div class="writers">
                <span class="writer-item">
                    <?php foreach($article['authors'] as $author): ?>
                        <?= $author ?>
                    <?php endforeach; ?>
                </span>, <?= date('F Y', $article['created_at']) ?></div>
            <div class="description">
                <?= $article['abstract']->abstract ?? ''; ?>
            </div>
            <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <h3>No Result</h3>
    <?php endif; ?>
</div>