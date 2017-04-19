<div class="search-results-item">
    <div class="publication-date">
        <?= date('F Y', strtotime($value['created_at'])) ?>
    </div>
    <div class="type">
        News
    </div>
    <div class="article-item">
        <h2><a href="<?= '/news/'.$value['url_key'] ?>"><?= $value['title'] ?></a></h2>
        <div class="writers"><span class="writer-item"><?= $value['editor'] ?></span></div>
        <div class="paragraph">
            <?= $value['short_description'] ?>
        </div>
    </div>
</div>