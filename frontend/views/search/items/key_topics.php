<li class="search-results-media-item">
    <div class="img-holder">
        <div class="img">
            <img src="<?= '/uploads/topics/'.$value['image_link'] ?>" alt="<?= $value['title'] ?>">
        </div>
    </div>
    <div class="link"><a href="<?= '/key-topics/'.$value['url_key'] ?>"><?= $value['title'] ?></a></div>
    <div class="location"><?= $value['short_description'] ?></div>
</li>