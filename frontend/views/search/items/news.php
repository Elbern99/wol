<div class="search-results-item">
    <div class="publication-date">
        <?= date('F Y', strtotime($value['created_at'])) ?>
    </div>
    <div class="type">
        News
    </div>
    <div class="description-center">
        <h2><a href="<?= '/news/'.$value['url_key'] ?>"><?= $value['title'] ?></a></h2>
        <div class="name"><?= $value['editor'] ?></div>
        <p>
            <?= $value['short_description'] ?>
        </p>
    </div>
</div>