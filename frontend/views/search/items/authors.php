<li class="search-results-media-item">
    <div class="img-holder">
        <div class="img">
            <img src="<?= $value['avatar'] ?>" alt="<?= $value['name'] ?>">
        </div>
    </div>
    <div class="link"><a href="<?= $value['url'] ?>"><?= $value['name'] ?></a></div>
    <div class="location"><?= $value['affiliation'] ?? '' ?></div>
</li>