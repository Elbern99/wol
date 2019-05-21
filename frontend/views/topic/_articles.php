<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<ul class="post-list">
    <?php foreach($collection as $article): ?>
    <li class="post-item">
        <ul class="article-rubrics-list">
            <?php foreach($article['category'] as $link): ?>
                <li><?= $link ?></li>
            <?php endforeach; ?>
        </ul>
        <h2>
            <a href="<?= $article['url'] ?>"><?= $article['title'] ?></a>
            <?php if ($article['isShowLabel']): ?>
                <span class="version-label">Updated</span>
            <?php endif; ?>
        </h2>
        <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
        <div class="writers">
            <?php foreach($article['authors'] as $author): ?><span class="writer-item"><?= $author ?></span><?php endforeach; ?>, <?= date('F Y', $article['created_at']) ?>
        </div>
        <div class="description">
            <?= $article['abstract']->abstract ?? ''; ?>
        </div>
        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
    </li>
    <?php endforeach; ?>
</ul>

<?php if ($articlesCount > $articleLimit): ?>
    <?php $params = ['/topic/articles', 'article_limit' => $articleLimit, 'topic_id' => $topicId]; ?>
    <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray align-center', 'id' => 'load-articles']) ?>
<?php else: ?>
    <?php $params = ['/topic/articles', 'topic_id' => $topicId]; ?>
    <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray align-center', 'id' => 'load-articles']) ?>
<?php endif; ?>