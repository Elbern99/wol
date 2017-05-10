<div class="search-results-item">
    <div class="publication-date">
        <?= date('Y F', strtotime($value['date'])) ?>
    </div>
    <div class="type">
        IZA discussion paper
    </div>
    <div class="article-item">
        <h2><a href="<?= $value['identifier'] ?>" target="blank"><?= $value['title'] ?></a></h2>
        <div class="writers">
            <?php if (is_array($value['creator'])): ?>
                <?php foreach($value['creator'] as $owner): ?>
                    <span class="writer-item"><?= $owner ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="writer-item"><?= $value['creator'] ?></span>
            <?php endif; ?>
        </div>
        <div class="description">
            <?= $value['description']; ?>
        </div>
        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
    </div>
</div>