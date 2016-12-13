<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="search">
    <div class="search-top">
        <?php $form = ActiveForm::begin(['action'=>'/search/result', 'id' => 'header-search-form']); ?>
            <span class="icon-search"></span>
             <?= Html::submitButton(Html::tag('span',Yii::t('app/form', 'search')), ['class' => 'btn-border-blue btn-center']) ?>
            <div class="search-holder">
                <?= Html::input('search', 'search', null, ['placeholder'=>"Keyword(s) or name", 'class'=>"form-control-decor", 'id'=>'header-search-input']) ?>
            </div>
        <?php ActiveForm::end(); ?>
            <div id="header-search-dropdown">
                
            </div>
    </div>
    <div class="search-bottom">
        <a href="<?= Url::to('/search/advanced') ?>">advanced search</a>
    </div>
</div>
<?php $this->registerJsFile('/js/search.js', ['depends' => ['yii\web\YiiAsset']]); ?>