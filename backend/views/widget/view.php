<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use backend\components\editor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Widget */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Widget'), 'url' => Url::toRoute('/widget')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'name')->textInput(['readonly' => ($model->id) ? true : false]) ?>
                <?= $form->field($model, 'text')->widget(CKEditor::className(), [
                    'options' => ['rows' => 10],
                    'preset' => 'standard',
                    'clientOptions'=> [
                        'allowedContent' => true,
                        'enterMode' => 2,
                        'forceEnterMode'=>false,
                        'shiftEnterMode'=>1
                    ]
                ]) ?>

            <?php if ($model->name == 'home_featured_article') : ?>
                <div class="form-group">
                    <span>If you want to add <b>"Updated"</b> indicator, You should to paste the following code <code><?= htmlspecialchars('<span class="version-label">Updated</span>') ?></code></span>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php $this->registerJs("CKEDITOR.config.extraPlugins = 'h1_b,h2_b,h3_b';",5); ?>