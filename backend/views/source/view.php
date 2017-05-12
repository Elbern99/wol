<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\UrlRewrite */
/* @var $form ActiveForm */
$this->title = Yii::t('app.menu', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Source'), 'url' => Url::toRoute('/source')];
$this->params['breadcrumbs'][] = $this->title;
$items = $sourceModel->getItems();
?>
<div class="view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($sourceModel, 'source') ?>
                <?= $form->field($sourceModel, 'website') ?>

                <?php //$form->field($sourceModel, 'types')->checkboxList(, ['separator' => '<br>'])->label('Type') ?>
                <div class="form-group field-datasource-types required has-success">
                    <label class="control-label">Type</label>
                    <input name="DataSource[types]" value="" type="hidden">
                    <div id="datasource-types" aria-required="true" aria-invalid="false">
                        <?php foreach($sourceModel->types as $type): ?> 
                        <label>
                            <input name="DataSource[types][<?= $type['id'] ?>][taxonomy_id]" value="<?= $type['taxonomy_id'] ?>" type="checkbox" checked> <?= $items[$type['taxonomy_id']] ?>
                            <input name="DataSource[types][<?= $type['id'] ?>][additional_taxonomy_id]" value="<?= $type['additional_taxonomy_id'] ?>" type="checkbox" checked> <?= $items[$type['additional_taxonomy_id']] ?>
                        </label>
                        <br>
                        <?php endforeach; ?>
                    </div>
                    <div class="help-block"></div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>