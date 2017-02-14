<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php
$prefixTitle = common\modules\settings\SettingsRepository::get('title_prefix');
$this->title = $prefixTitle.'Advanced Search';
$this->params['breadcrumbs'][] = Html::encode('Advanced Search');

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($this->title)
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($this->title)
]);

$this->registerJsFile('/js/plugins/jqueryui.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/tag-it.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/pages/advanced-search.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile('/css/plugins/jquery.tagit.css');
$this->registerCssFile('/css/plugins/tagit.ui-zendesk.css');
?>

<div class="container content-inner search-site">
    <div class="content-inner-text">
        <div class="article-head">
            <div class="breadcrumbs">
                <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
            </div>
            <h1>Search the site</h1>
        </div>

        <?php $form = ActiveForm::begin(['class' => 'header-search-form']); ?>
            <div class="search">
                <span class="icon-search"></span>
                <div class="search-holder">
                    <?= $form->field($search, 'search_phrase')->textInput(['class'=>"form-control-decor", 'placeholder'=>"Search for "])->label('') ?>
                </div>
            </div>

            <div class="content-types">
                <h3>in these content types</h3>
                <div class="grid-line one title-checkboxes">
                    <div class="grid-item form-item">
                        <div class="select-clear-all">
                            <span class="clear-all">Clear all</span>
                            <span class="select-all">Select all</span>
                        </div>
                    </div>
                </div>
                <div class="grid checkboxes">
                    <div class="grid-line three">
                        <?= $form->field($search, 'types')->checkboxList($search->getHeadingFilter(),[
                                'item'=> function($index, $label, $name, $checked, $value) {
                                    return '<div class="grid-item"><div class="form-item"><label class="custom-checkbox">'.
                                    Html::checkbox($name, $checked, [
                                        'value' => $value,
                                    ]).'<span class="label-text">'.$label.'</span></label></div></div>';
                                }
                            ])->label('');
                        ?>
                    </div>
                </div>
            </div>

            <div class="finding-results">
                <h3>finding results that have</h3>

                <div class="my-tags-holder">
                    <div class="label-holder">
                        <div class="label-text label-text-custom">all of these words</div>
                    </div>
                    <ul class="my-tags-list all-words-tags-list"></ul>
                    <?= Html::activeInput('hidden', $search, 'all_words', ['class'=>"my-single-field"]) ?>
                </div>

                <div class="form-line">
                    <div class="label-holder">
                        <label for="advancedsearchform-exact_phrase">this exact phrase</label>
                    </div>
                    <div class="form-control-holder">
                        <?= Html::activeInput('text', $search, 'exact_phrase', ['class'=>"form-control", 'placeholder'=>"Enter phrase without quotes"]) ?>
                    </div>
                </div>

                <div class="my-tags-holder">
                    <div class="label-holder">
                        <div class="label-text label-text-custom">one or more of these words</div>
                    </div>
                    <ul class="my-tags-list one-or-more-my-tags-list"></ul>
                    <?= Html::activeInput('hidden', $search, 'one_more_words', ['class'=>"my-single-field"]) ?>
                </div>
            </div>

            <div class="excluding-results">
                <h3>excluding results that have</h3>
                <div class="my-tags-holder">
                    <div class="label-holder">
                        <div class="label-text label-text-custom">any of these words</div>
                    </div>
                    <ul class="my-tags-list one-or-more-my-tags-list"></ul>
                    <?= Html::activeInput('hidden', $search, 'any_words', ['class'=>"my-single-field"]) ?>
                </div>
            </div>
            <button class="btn-blue-large" type="submit">search</button>
        <?php ActiveForm::end(); ?>
    </div>
</div>