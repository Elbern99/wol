<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Author;
use frontend\components\articles\SubjectAreas;
?>

<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.$author['author']->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Editorial board'), 'url' => Url::toRoute(['/editorial-board'])];
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
                    <li><a href="" class="js-widget">Subject areas</a></li>
                    <li><a href="" class="js-widget">Authors</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item blue js-tab-hidden expand-more tab-item-subject-areas">
                        <?= SubjectAreas::widget(['category' => $subjectAreas]) ?>
                    </div>
                    <div class="tab-item blue js-tab-hidden expand-more">
                        <?= \frontend\components\widget\AuthorLetterWidget::widget(['mode' => 'profile', 'type' => $type]); ?>
                    </div>
                </div>
            </div>
            <div class="contributor-profile">
                <div class="img-holder img-holder-bg">
                    <div class="img" style="background-image: url(<?= Author::getImageUrl($author['author']->avatar)?>)"></div>
                </div>
                <div class="description">
                    <div class="name"><?= $author['author']->name ?></div>
                    <?php $areas = $author['author']->getAuthorCategoriesArray(); ?>
                    <?php if (is_array($areas) && count($areas)): ?>
                    <div class="item-role-subject">
                        <strong>
                        <?php
                            foreach ($author['roles'] as $role) {
                                if (preg_match("/Editor/", $role)) {
                                    echo $role.", ";
                                }
                            }
                         ?></strong>
                        <?php $arealinks = []; ?>
                        <?php foreach($areas as $area): ?>
                            <?php $this->beginBlock('arealink'); ?><span><?= Html::a($area['title'], $area['url_key']) ?></span><?php $this->endBlock(); ?>
                            <?php $arealinks[] = $this->blocks['arealink']; ?>
                        <?php endforeach; ?>
                        <?= implode(', ', $arealinks); ?>
                    </div>
                    <?php endif; ?>
                    <p class="short-desc"><?= $author['affiliation'] ?></p>
                    <?php if (count($author['roles'])): ?>
                    <div class="item">
                        <h2>IZA World of Labor role</h2>
                        <p><?= implode(', ', $author['roles']) ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($author['position']->current) && $author['position']->current): ?>
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
                    <?php endif; ?>
                    <?php if($author['interests']): ?>
                    <div class="item">
                        <h2>Research interest</h2>
                        <p><?= $author['interests'] ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if ($author['author']->url): ?>
                    <div class="item">
                        <h2>Website</h2>
                        <p><a href="<?= $author['author']->url ?>" target="blank"><?= $author['author']->url ?></a></p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($author['position']->advisory) && $author['position']->advisory): ?>
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
                    <?php endif; ?>
                    <?php if(isset($author['position']->past) && $author['position']->past): ?>
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
                    <?php endif; ?>
                    <?php if($author['degree'] ): ?>
                    <div class="item">
                        <h2>Qualifications</h2>
                        <p><?= $author['degree'] ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if(count($author['publications'])): ?>
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
                    <?php endif; ?>
                </div>
            </div>
            <?php if(count($author['articles'])): ?>
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
                            <div class="writers">
                                <?php foreach($article['authors'] as $owner): ?><span class="writer-item"><a href="<?= $owner->getUrl() ?>"><?= $owner->name  ?></a></span><?php endforeach; ?>,
                                <?= date('F Y', $article['created_at']) ?>
                            </div>
                            <div class="description">
                                <?= $article['abstract']->abstract ?? ''; ?>
                            </div>
                            <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
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
                        <a href="" class="title">Editors</a>
                        <div class="text">
                            <?= \frontend\components\widget\AuthorLetterWidget::widget(['mode' => 'profile', 'type' => $type]); ?>
                        </div>
                    </li>
                </ul>
            </div>
            <?= $widgets->getPageWidget('ask_the_expert') ?>
        </aside>
    </div>
</div>
