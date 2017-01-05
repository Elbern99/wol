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

<div class="container find-expert">
    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
    </div>

    <div class="search-results-top">
        <?php foreach($collection as $author): ?>

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
                
        <?php endforeach; ?>
                
        <?= LinkPager::widget([
            'pagination' => $paginate,
        ]); ?>
    </div>
</div>
