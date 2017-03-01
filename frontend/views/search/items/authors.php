<li class="search-results-media-item">
    <a href="<?= $value['url'] ?>" class="img-holder img-holder-bg">
        <div class="img" style="background-image: url(<?= $value['avatar'] ?>)"></div>
    </a>
    <div class="name"><a href="<?= $value['url'] ?>"><?= $value['name'] ?></a></div>
    <p class="location"><?= $value['affiliation'] ?? '' ?></p>
</li>