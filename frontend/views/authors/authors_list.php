<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Author;
use yii\widgets\ActiveForm;
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
    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
    </div>
    <div class="content-inner">
        <div class="content-inner-text">
            <div class="mobile-filter-holder custom-tabs-holder">
                <ul class="mobile-filter-list">
                    <li><a href="" class="js-widget">Author Search</a></li>
                    <li><a href="" class="js-widget">Authors</a></li>
                </ul>
                <div class="mobile-filter-items custom-tabs">
                    <div class="tab-item js-tab-hidden">
                        <div class="author-search-form search">
                            <div class="search-holder">
                                <div class="search-top">
                                    <span class="icon-search"></span>
                                    <?php $form = ActiveForm::begin(['action' => '/authors']); ?>
                                    <?= $form->field($searchModel, 'search')->textInput(['class'=>"form-control-decor", 'placeholder'=>"Enter author name"])->label('') ?>
                                    <button type="submit" class="btn-border-blue">
                                        <span class="inner">search</span>
                                    </button>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-item blue js-tab-hidden expand-more">
                        <?php $alphas = range('A', 'Z'); ?>
                        <ul class="abs-list">
                            <?php foreach ($alphas as $letter): ?>
                                <li><a class="profile-author-letter" href="<?= Url::to(['/authors', 'filter' => $letter]) ?>"><span class="text"><?= $letter ?></span></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <ul class="search-results-media-list">
                <?php foreach($collection as $author): ?>
                    <li class="search-results-media-item">
                        <div class="img-holder img-holder-bg">
                            <div class="img" style="background-image: url(<?= $author['avatar'] ?>)"></div>
                        </div>
                        <div class="name">
                            <a href="<?= Author::getAuthorUrl($author['url_key']) ?>">
                                <?= $author['name']->first_name ?>
                                <?= $author['name']->middle_name ?>
                                <?= $author['name']->last_name ?>
                            </a>
                        </div>
                        <p class="location"><?= $author['affiliation'] ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?= LinkPager::widget([
                'pagination' => $paginate,
            ]); ?>
        </div>
        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-articles-filter hide-mobile">
                <ul class="sidebar-accrodion-list">
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">Author Search</a>
                        <div class="text author-search-form search">
                            <div class="search-holder">
                                <div class="search-top">
                                    <span class="icon-search"></span>
                                    <?php $form = ActiveForm::begin(['action' => '/authors']); ?>
                                    <?= $form->field($searchModel, 'search')->textInput(['class'=>"form-control-decor", 'placeholder'=>"Enter author name"])->label('') ?>
                                    <button type="submit" class="btn-border-blue">
                                        <span class="inner">search</span>
                                    </button>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="sidebar-accrodion-item is-open">
                        <a href="" class="title">Authors</a>
                        <div class="text">
                            <?php $alphas = range('A', 'Z'); ?>
                            <ul class="abs-list">
                                <?php foreach ($alphas as $letter): ?>
                                    <li><a class="profile-author-letter" href="<?= Url::to(['/authors', 'filter' => $letter]) ?>"><span class="text"><?= $letter ?></span></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
</div>
