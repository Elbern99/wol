<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model backend\models\AdminInterfaceUpload */

$this->title = Yii::t('app.menu', 'Versions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
   
    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="form-group">
                <?= Html::activeDropDownList($model, 'type', $model->getActionType()) ?> 
            </div>
            <div class="form-group">
                <?= Html::activeFileInput($model, 'archive') ?> 
            </div>
            
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app/form', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>