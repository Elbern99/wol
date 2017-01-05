<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Author;
use frontend\components\articles\SubjectAreas;
?>

<?php
$this->title = $author['author']->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Authors'), 'url' => Url::toRoute(['/authors'])];
$this->params['breadcrumbs'][] = Html::encode($this->title);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($this->title)
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($this->title)
]);

$this->registerJsFile('/js/pages/profile.js', ['depends' => ['yii\web\YiiAsset']]);
?>

<div class="container find-expert">
    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <div class="contributor-profile">
                <div class="img" style="background-image: url(<?= Author::getImageUrl($author['author']->avatar)?>)"></div>
                <div class="description">
                    <div class="name"><?= $author['author']->name ?></div>
                    <p class="short-desc"><?= $author['degree'] ?></p>
                    <div class="quote">
                        <em><?= $author['testimonial'] ?></em>
                    </div>

                    <div class="item">
                        <h2>IZA World of Labor role</h2>
                        <p><?= implode(', ', $author['roles']) ?></p>
                    </div>

                    <div class="item">
                        <h2>Positions/functions as a policy advisor</h2>
                        <p><?= $author['position']->current ?></p>
                        <p><?= $author['position']->advisory ?></p>
                    </div>

                    <div class="item">
                        <h2>Website</h2>
                        <p><a href="<?= $author['author']->url ?>"><?= $author['author']->url ?></a></p>
                    </div>

                    <div class="item">
                        <h2>Affiliations</h2>
                        <p><?= $author['affiliation'] ?></p>
                    </div>

                    <div class="item">
                        <h2>Past positions</h2>
                        <p><?= $author['position']->past ?></p>
                    </div>

                    <div class="item">
                        <h2>Research interest</h2>
                        <p><?= $author['interests'] ?></p>
                    </div>

                    <div class="item">
                        <h2>Qualifications</h2>
                        <p><?= $author['experience_type'] ?></p>
                    </div>

                    <div class="selected-publications">
                        <h2>Selected publications</h2>

                        <ul class="selected-publications-list">
                            <?php foreach ($author['publications'] as $publication): ?>
                            <li>
                                <p><?= $publication ?></p>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="articles">
                <div class="widget-title medium">Articles</div>
                <ul class="articles-list">
                <?php foreach($author['articles'] as $article): ?>
                    <li class="article-item">
                        <ul class="article-rubrics-list">
                            <?php foreach($article['category'] as $link): ?>
                                <li><?= $link ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <h2><a href="<?= $article['url'] ?>"><?= $article['title'] ?></a></h2>
                        <h3><?= $article['teaser']->teaser ?? ''; ?></h3>
                        <div class="publish"><a href=""><?= $article['availability']  ?></a>, <?= date('F Y', $article['created_at']) ?></div>
                        <div class="description">
                            <?= $article['abstract']->abstract ?? ''; ?>
                        </div>
                        <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                    </li>
                </ul>
                <?php endforeach; ?>
            </div>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">subject areas</a>
                        <div class="text is-open">
                            <div class="text-inner">
                                <?= SubjectAreas::widget(['category' => $subjectAreas]) ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="sidebar-widget">
                <div class="widget-title">Authors</div>
                <?php $alphas = range('A', 'Z'); ?>
                <ul class="socials-list socials-vertical-list">
                    <?php foreach ($alphas as $letter): ?>
                    <li><a class="profile-author-letter" href="<?= Url::to('/authors/letter/') ?>" data-letter="<?=$letter?>"><span class="text"><?= $letter ?></span></a></li>
                    <?php endforeach; ?>
                </ul>
                <div id="author-letter-result"></div>
            </div>
            <?php if (isset($widget['text'])): ?>
            <div class="sidebar-widget sidebar-widget-journals">
                <?= $widget['text'] ?>
            </div>
            <?php endif; ?>
        </aside>
    </div>
</div>
