<li class="search-results-media-item">
    <div class="img-holder img-holder-bg">
        <div class="img" style="background-image: url(<?= $value['avatar'] ?>)"></div>
    </div>
    <div class="name"><a href="<?= $value['url'] ?>"><?= $value['name'] ?></a></div>
    <p class="location"><?= $value['affiliation'] ?? '' ?></p>
</li>