<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\helpers\Country;
?>

<?php
$this->title = 'Find an expert';
$this->params['breadcrumbs'][] = Html::encode($this->title);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($this->title)
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($this->title)
]);

$this->registerJsFile('/js/pages/find-expert.js', ['depends' => ['yii\web\YiiAsset']]);
?>
<?php $form = ActiveForm::begin(); ?>
<div class="container find-expert">
    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
        <h1>Find an expert</h1>
        <p class="large-paragraph">With over XXX experts affiliated with IZA World of Labor, we allow access to the leading thought leaders on labor subjects across the world.</p>
        <p>If you can’t find the expert you are looking for please <a href="">get in touch.</a></p>
    </div>
    
    
    <div class="search-results-top">
        <div class="search">
            <div class="search-top">
                <span class="icon-search"></span>
                <button type="submit" class="btn-blue btn-center">
                        <span class="inner">
                            search
                        </span>
                </button>
                <div class="search-holder">
                    <?= $form->field($search, 'search_phrase')->textInput(['class'=>"form-control-decor", 'placeholder'=>"Enter expertise or author name"])->label('') ?>
                </div>
            </div>
        </div>
        
        <div class="mobile-filter-holder">
            <div class="search-results-top-filter">
                <strong><?= count($expertCount) ?> were found</strong>
                <a href="" class="filter-mobile-link">Filter</a>
                <a href="" class="refine-mobile-link">Refine</a>
            </div>
            <div class="mobile-filter">
                <div class="mobile-filter-container"></div>
                <div class="mobile-filter-bottom">
                    <button type="submit" class="btn-blue-large">update</button>
                    <a href="" class="btn-no-style">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content-inner">
        <?php Pjax::begin(['linkSelector' => '.btn-gray']); ?>
        <div class="content-inner-text">
            <ul class="search-results-media-list">
                
                <?php foreach ($expertCollection as $expert): ?>
                <li class="search-results-media-item">
                    <div class="img-holder">
                        <div class="img">
                            <img src="<?= $expert['avatar'] ?>" alt="">
                        </div>
                    </div>
                    <div class="name"><?= $expert['name']->first_name ?> <?= $expert['name']->middle_name ?> <?= $expert['name']->last_name ?></div>
                    <p class="location"><?= $expert['affiliation'] ?></p>
                    <p><strong>Expertise:</strong> <?= implode(',', $expert['expertise'])?></p>
                    <p><strong>Media Experience:</strong> <?= implode(',', $expert['experience_type'])?></p>
                    <p><strong>Languages:</strong> <?php array_walk($expert['language'], function($value) { echo Country::getCountryName($value)."\n"; }) ?></p>
                    <p><strong>Country:</strong><?php array_walk($expert['author_country'], function($value) { echo Country::getCountryName($value)."\n"; }) ?></p>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php if (!Yii::$app->request->isPost): ?>
                <?php if ($expertCount > $limit): ?>
                    <?= Html::a("show more", Url::current(['limit' => $limit]), ['class' => 'btn-gray align-center']) ?>
                <?php else: ?>
                    <?php if (Yii::$app->request->get('limit')): ?>
                        <?= Html::a("clear", Url::current(['limit' => 0]), ['class' => 'btn-gray align-center']) ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php Pjax::end(); ?>
        <aside class="sidebar-right">
            <div class="filter-clone-holder">
                <div class="filter-clone">
                    <div class="sidebar-widget sidebar-widget-filter">
                        <h3>Filter by</h3>
                        
                        <ul class="sidebar-accrodion-list">
                            <?php if (isset($filter['author_country'])): ?>
                            <li class="sidebar-accrodion-item is-open">
                                <a href="" class="title">country</a>
                                <div class="text">
                                    <div class="checkbox-list more-extra-list">
                                        <?= Html::activeCheckboxList($search, 'author_country', $filter['author_country'], ['item' => function($index, $label, $name, $checked, $value) {
                                            
                                            return Html::tag('div', Html::checkbox($name, $checked, [
                                                'labelOptions'=>['class' => 'def-checkbox light'],
                                                'value' => $value,
                                                'label' => '<span class="label-text">'.Country::getCountryName($label).'</span>',
                                            ]), ['class' => 'item']);
                                        }]) ?>

                                    </div>
                                    <?php if(count($filter['author_country']) > 13): ?>
                                        <a href="" class="more-link">
                                            <span class="more">More</span>
                                            <span class="less">Less</span>
                                        </a>
                                    <?php endif ?>

                                    <a href="" class="clear-all">Clear all</a>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($filter['language'])): ?>
                            <li class="sidebar-accrodion-item">
                                <a href="" class="title">language</a>
                                <div class="text">
                                    <div class="checkbox-list more-extra-list">
                                        <?= Html::activeCheckboxList($search, 'language', $filter['language'], ['item' => function($index, $label, $name, $checked, $value) {
                                            return Html::tag('div', Html::checkbox($name, $checked, [
                                                'labelOptions'=>['class' => 'def-checkbox light'],
                                                'value' => $value,
                                                'label' => '<span class="label-text">'.Country::getCountryName($label).'</span>',
                                            ]), ['class' => 'item']);
                                        }]) ?>
                                    </div>
                                    <?php if(count($filter['language']) > 13): ?>
                                        <a href="" class="more-link">
                                            <span class="more">More</span>
                                            <span class="less">Less</span>
                                        </a>
                                    <?php endif ?>

                                    <a href="" class="clear-all">Clear all</a>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($filter['expertise'])): ?>
                            <li class="sidebar-accrodion-item">
                                <a href="" class="title">expertise</a>
                                <div class="text expand-more">
                                    <div class="checkbox-list more-extra-list">
                                        <?= Html::activeCheckboxList($search, 'expertise', $filter['expertise'], ['item' => function($index, $label, $name, $checked, $value) {
                                            return Html::tag('div', Html::checkbox($name, $checked, [
                                                'labelOptions'=>['class' => 'def-checkbox light'],
                                                'value' => $value,
                                                'label' => '<span class="label-text">'.$label.'</span>',
                                            ]), ['class' => 'item']);
                                        }]) ?>
                                    </div>

                                    <?php if(count($filter['expertise']) > 13): ?>
                                        <a href="" class="more-link">
                                            <span class="more">More</span>
                                            <span class="less">Less</span>
                                        </a>
                                    <?php endif ?>

                                    <a href="" class="clear-all">Clear all</a>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($filter['experience_type'])): ?>
                            <li class="sidebar-accrodion-item">
                                <a href="" class="title">media experience</a>
                                <div class="text">
                                    <div class="checkbox-list">
                                        <?= Html::activeCheckboxList($search, 'experience_type', $filter['experience_type'], ['item' => function($index, $label, $name, $checked, $value) {
                                            return Html::tag('div', Html::checkbox($name, $checked, [
                                                'labelOptions' => ['class' => 'def-checkbox light'],
                                                'value' => $value,
                                                'label' => '<span class="label-text">'.$label.'</span>',
                                            ]), ['class' => 'item']);
                                        }]) ?>
                                    </div>
                                    <?php if(count($filter['experience_type']) > 13): ?>
                                        <a href="" class="more-link">
                                            <span class="more">More</span>
                                            <span class="less">Less</span>
                                        </a>
                                    <?php endif ?>

                                    <a href="" class="clear-all">Clear all</a>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
<?php ActiveForm::end(); ?>