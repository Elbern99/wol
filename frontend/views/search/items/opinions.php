<div class="search-results-item">
    <div class="publication-date">
        <?= date('F Y', strtotime($value['created_at'])) ?>
    </div>
    <div class="type">
        Opinion
    </div>
    <div class="description-center">
        <h2><a href="<?= '/opinions/'.$value['url_key'] ?>"><?= $value['title'] ?></a></h2>
        <p>
            <?= $value['short_description'] ?>
        </p>
    </div>
</div>