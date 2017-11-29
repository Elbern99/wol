<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\helpers\AdminFunctionHelper;
use nezhelskoy\highlight\HighlightAsset;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;

HighlightAsset::register($this);
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app.menu', 'Sitemap XML');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!$exists) : ?>
        <div class="row content">
            <div class="col-sm-12 sidenav">
                <p><?= Yii::t('app/text', '"{file}" not found.', ['file' => common\components\Sitemap::filePath()]); ?></p>
            </div>
        </div>

    <?php else : ?>
        <?php $this->beginBlock('xml'); ?>
        <div class="row content">
            <div class="col-sm-12 sidenav">
                <?= Html::tag('pre', Html::tag('code', htmlspecialchars(common\components\Sitemap::getContent()), ['class' => 'xml'])); ?>
            </div>
        </div>
        <?php $this->endBlock(); ?>

        <?php $this->beginBlock('textForm'); ?>
        <?php if ($writable) : ?>
            <div class="row content">
                <div class="col-sm-12 sidenav">
                    <?php
                    $form = ActiveForm::begin([
                            'id' => 'xml-text-form',
                            'options' => ['class' => 'form-horizontal'],
                    ]);
                    ?>
                    <?= $form->field($textModel, 'xml')->textarea(['style' => 'min-height: 600px;']); ?>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?= Html::submitButton(Yii::t('app', 'Overwrite'), ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>                
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php $this->endBlock(); ?>

        <?php $this->beginBlock('uploadForm'); ?>
        <?php if ($writable) : ?>
            <div class="row content">
                <div class="col-sm-12 sidenav">
                    <?php
                    $form = ActiveForm::begin([
                            'id' => 'xml-upload-form',
                            'options' => ['class' => 'form-horizontal'],
                    ]);
                    ?>
                    <?= $form->field($fileModel, 'xml')->fileInput(); ?>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?= Html::submitButton(Yii::t('app', 'Overwrite'), ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>                
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Current XML'),
                    'content' => $this->blocks['xml'],
                    'options' => ['id' => 'xmldisplay'],
                    'active' => !$activeTab
                ],
                [
                    'label' => Yii::t('app', 'Manual Editor'),
                    'content' => $this->blocks['textForm'],
                    'headerOptions' => [],
                    'options' => ['id' => 'xmleditor'],
                    'visible' => $writable,
                    'active' => $activeTab == 'text',
                ],
                [
                    'label' => Yii::t('app', 'Upload XML'),
                    'content' => $this->blocks['uploadForm'],
                    'headerOptions' => [],
                    'options' => ['id' => 'xmlupload'],
                    'visible' => $writable,
                    'active' => $activeTab == 'text',
                ],
            ],
        ]);
        ?>    
    <?php endif; ?>
</div>