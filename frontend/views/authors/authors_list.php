<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use common\models\Author;
?>

<?php
$this->title = 'Authors';
$this->params['breadcrumbs'][] = Html::encode($this->title);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($this->title)
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($this->title)
]);
?>

<div class="container authors-page">
    <div class="content-inner">
        <div class="content-inner-text">
            <div class="article-head">
                <div class="breadcrumbs">
                    <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
                </div>
            </div>

            <ul class="search-results-media-list">
                <?php foreach($collection as $author): ?>
                    <li class="search-results-media-item">
                        <div class="name">
                            <a href="<?= Author::getAuthorUrl($author['url_key']) ?>">
                                <?= $author['name']->first_name ?>
                                <?= $author['name']->last_name ?>
                                <?= $author['name']->middle_name ?>
                            </a>
                        </div>
                        <p class="location"><?= $author['affiliation'] ?></p>

                        <?php foreach ($author['articles'] as $article): ?>
                            <a href="/articles/<?= $article['seo'] ?>"><?= $article['title'] ?></a>
                        <?php endforeach; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?= LinkPager::widget([
                'pagination' => $paginate,
            ]); ?>
        </div>
    </div>
</div>
