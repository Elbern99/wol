<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUploadUI;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model backend\models\AdminInterfaceUpload */

$this->title = Yii::t('app.menu', 'Upload');
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
            
            <?= FileUploadUI::widget([
                'model' => $model,
                'attribute' => 'archive',
                'url' => ['/admin-interface/upload'],
                'gallery' => false,
                'fieldOptions' => [
                    'accept' => 'application/zip'
                ],
                'clientOptions' => [
                    //'maxFileSize' => 20
                ],
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                                            console.log(e);
                                            console.log(data);
                                        }',
                    'fileuploadfail' => 'function(e, data) {
                                            console.log(e);
                                            console.log(data);
                                        }',
                ],
            ]); ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>