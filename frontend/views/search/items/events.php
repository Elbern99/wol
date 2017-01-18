<div class="search-results-item">
    <div class="publication-date">
        <?= date('F Y', strtotime($value['date_to'])) ?> - <?= date('F Y', strtotime($value['date_from'])) ?>
    </div>
    <div class="type">
        Event
    </div>
    <div class="description-center">
        <h2><a href="<?= '/events/'.$value['url_key'] ?>"><?= $value['title'] ?></a></h2>
        <div class="name"><a href=""><?= $value['location'] ?></a></div>
    </div>
</div>