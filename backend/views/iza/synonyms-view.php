<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\UrlRewrite */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Synonyms'), 'url' => Url::toRoute('/iza/synonyms')];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$this->registerJsFile(Url::to(['/js/dynamically_fields.js']), ['depends' => [\yii\web\JqueryAsset::className()]]);

$config = [
    'wrapper' => '.input_fields_wrap',
    'add_button' => '.add_field_button',
    'model_field_name' => Html::getInputName($model, 'synonyms'),
    'fields' => [
        ['name' => 'synonym', 'type' => 'text', 'label' => 'Synonym']
    ],
    'data' => []
];

$this->registerJs("dynamicallyFields.init(".json_encode($config).");", 3);
?>

<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>

            <div class="form-group input_fields_wrap">
                <div>
                    <p><button class="add_field_button">Add More Synonym</button></p>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>