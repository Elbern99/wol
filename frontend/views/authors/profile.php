<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Author;
use frontend\components\articles\SubjectAreas;
?>

<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.$author['author']->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Authors'), 'url' => Url::toRoute(['/authors'])];
$this->params['breadcrumbs'][] = Html::encode($author['author']->name);

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

<div class="container contributor-profile-page">
    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
    </div>

    <div class="content-inner">
        <div class="content-inner-text">
            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li><a href="" class="js-widget">subject areas</a></li>
                    <!-- <li><a href="" class="js-widget">trending topics</a></li>-->
                    <li><a href="" class="js-widget">authors</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item blue js-tab-hidden expand-more">
                        <?= SubjectAreas::widget(['category' => $subjectAreas]) ?>
                    </div>
                    <!-- <div class="tab-item js-tab-hidden expand-more">
                        test 2
                    </div>-->
                    <div class="tab-item blue js-tab-hidden expand-more">
                        <?php $alphas = range('A', 'Z'); ?>
                        <ul class="abs-list">
                            <?php foreach ($alphas as $letter): ?>
                                <li><a class="profile-author-letter" href="<?= Url::to('/authors/letter/') ?>" data-letter="<?=$letter?>"><span class="text"><?= $letter ?></span></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="author-letter-result"></div>
                    </div>
                </div>
            </div>
            <div class="contributor-profile">
                <div class="img-holder img-holder-bg">
                    <div class="img" style="background-image: url(<?= Author::getImageUrl($author['author']->avatar)?>)"></div>
                </div>
                <div class="description">
                    <div class="name"><?= $author['author']->name ?></div>
                    <p class="short-desc"><?= $author['affiliation'] ?></p>
                    <div class="quote">
                        <em><?= $author['testimonial'] ?></em>
                    </div>

                    <div class="item">
                        <h2>IZA World of Labor role</h2>
                        <p><?= implode(', ', array_map(function($role) {
                           return Yii::t('app/text', $role);
                        }, $author['roles'])) ?></p>
                    </div>
                    <div class="item">
                        <h2>Current position</h2>
                        <?php if(is_array($author['position']->current)): ?>
                            <?php foreach($author['position']->current as $current): ?>
                                <p><?= $current ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                                <p><?= $author['position']->current ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="item">
                        <h2>Research interest</h2>
                        <p><?= $author['interests'] ?></p>
                    </div>
                    
                    <div class="item">
                        <h2>Website</h2>
                        <p><a href="<?= $author['author']->url ?>"><?= $author['author']->url ?></a></p>
                    </div>
                    
                    <div class="item">
                        <h2>Positions/functions as a policy advisor</h2>
                        <?php if(is_array($author['position']->advisory)): ?>
                            <?php foreach($author['position']->advisory as $advisory): ?>
                                <p><?= $advisory ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                                <p><?= $author['position']->advisory ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="item">
                        <h2>Affiliations</h2>
                        <p><?= $author['affiliation'] ?></p>
                    </div>

                    <div class="item">
                        <h2>Past positions</h2>
                        <?php if(is_array($author['position']->past)): ?>
                            <?php foreach($author['position']->past as $past): ?>
                                <p><?= $past ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                                <p><?= $author['position']->past ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="item">
                        <h2>Qualifications</h2>
                        <p><?= $author['degree'] ?></p>
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
                <div class="widget-title medium"><a href="/articles">article(s)</a></div>
                <ul class="other-articles-list">
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
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter hide-mobile">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item">
                        <a href="" class="title">subject areas</a>
                        <div class="text">
                             <?= SubjectAreas::widget(['category' => $subjectAreas]) ?>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item  is-open">
                        <a href="" class="title">Authors</a>
                        <div class="text">
                            <?php $alphas = range('A', 'Z'); ?>
                            <ul class="abs-list">
                                <?php foreach ($alphas as $letter): ?>
                                    <li><a class="profile-author-letter" href="<?= Url::to('/authors/letter/') ?>" data-letter="<?=$letter?>"><span class="text"><?= $letter ?></span></a></li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="author-letter-result"></div>
                        </div>
                    </li>
                </ul>
            </div>
            <?= $widgets->getPageWidget('ask_the_expert') ?>
        </aside>
    </div>
</div>
