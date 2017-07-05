<li class="search-results-media-item">
    <a href="<?= '/key-topics/'.$value['url_key'] ?>" class="img-holder img-holder-bg">
        <div class="img" style="background-image: url(<?= Yii::$app->imageCache->getImgSrc('/uploads/topics/'.$value['image_link'],'297','239') ?>)"></div>
    </a>
    <div class="link"><a href="<?= '/key-topics/'.$value['url_key'] ?>">TOPIC</a></div>
    <div class="name"><a href="<?= '/key-topics/'.$value['url_key'] ?>"><?= $value['title'] ?></a></div>
    <p class="location"><?= $value['short_description'] ?></p>
</li>