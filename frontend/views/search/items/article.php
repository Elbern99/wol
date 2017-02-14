<div class="search-results-item">
    <div class="publication-date">
        <?= date('F Y', $value['created_at']) ?>
    </div>
    <div class="type">
        Article
    </div>
    <div class="article-item">
        <h2><a href="<?= $value['url'] ?>"><?= $value['title'] ?></a></h2>
        <h3><?= $value['teaser']->teaser ?? ''; ?></h3>
        <div class="name">
            <?php foreach($value['authors'] as $owner): ?>
                <?php if (is_object($owner)): ?>
                <a href="<?= $owner->getUrl() ?>"><?= $owner->name  ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="description">
            <?= $value['abstract']->abstract ?? ''; ?>
        </div>
        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
    </div>
</div>