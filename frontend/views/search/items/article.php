<div class="search-results-item">
    <div class="publication-date">
        <?= date('F Y', $value['created_at']) ?>
    </div>
    <div class="type">
        Article
    </div>
    <div class="description-center">
        <h2><a href="<?= $value['url'] ?>"><?= $value['title'] ?></a></h2>
        <h3><?= $value['teaser']->teaser ?? ''; ?></h3>
        <div class="name"><a href=""><?= $value['availability'] ?></a></div>
        <div class="description">
            <?= $value['abstract']->abstract ?? ''; ?>
        </div>
        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
    </div>
</div>