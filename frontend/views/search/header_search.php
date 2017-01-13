<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use frontend\models\SearchForm;
?>

<?php $model = new SearchForm(); ?>
<div class="search">
    <div class="search-top">
        <?php $form = ActiveForm::begin(['action'=>'/search', 'options' => ['class' => 'header-search-form']]); ?>
            <span class="icon-search"></span>
             <?= Html::submitButton(Html::tag('span',Yii::t('app/form', 'search')), ['class' => 'btn-border-blue']) ?>
            <div class="search-holder">
                <?= $form->field($model, 'search_phrase')->textInput(['class'=>"form-control-decor header-search-input", 'placeholder'=>"Keyword(s) or name", 'autocomplete'=>"off"])->label('') ?>
            </div>
        <?php ActiveForm::end(); ?>
        <div class="header-search-dropdown"></div>
    </div>
    <div class="search-bottom">
        <a href="<?= Url::to('/search/advanced') ?>">advanced search</a>
    </div>
</div> 
<?php $this->registerJsFile('/js/search.js', ['depends' => ['yii\web\YiiAsset']]); ?>