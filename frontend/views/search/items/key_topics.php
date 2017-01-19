<li class="search-results-media-item">
    <div class="img">
        <img src="<?= '/uploads/topics/'.$value['image_link'] ?>" alt="<?= $value['title'] ?>">
    </div>
    <div class="link"><a href="<?= '/key-topics/'.$value['url_key'] ?>"><?= $value['title'] ?></a></div>
    <div class="name"><?= $value['short_description'] ?></div>
</li>