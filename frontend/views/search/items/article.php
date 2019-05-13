<?php $article = \common\models\Article::findOne($value['id']); ?>
<div class="search-results-item">
    <div class="publication-date">
        <?= date('F Y', $value['created_at']) ?>
    </div>
    <div class="type">
        Article
    </div>
    <div class="article-item">
        <h2>
            <a href="<?= $value['url'] ?>"><?= $value['title'] ?></a>
            <?php if ($article->isShowVersionLabel()): ?>
                <span class="version-label">Updated</span>
            <?php endif; ?>
        </h2>
        <h3><?= $value['teaser']->teaser ?? ''; ?></h3>
        <div class="writers">
            <?php foreach($value['authors'] as $owner): ?>
                <?php if (is_object($owner)): ?>
                <span class="writer-item"><a href="<?= $owner->getUrl() ?>"><?= $owner->name  ?></a></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="description">
            <?= $value['abstract']->abstract ?? ''; ?>
        </div>
        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
    </div>
</div>