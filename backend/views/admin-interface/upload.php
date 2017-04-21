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
            <div id="log-info"></div>
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
                                            if (data.jqXHR) {
                                                var result = JSON.parse(data.jqXHR.responseText);
                                                addToLog(result, "success");
                                            }
                                        }',
                    'fileuploadfail' => 'function(e, data) {
                                            if (data.jqXHR) {
                                                var result = JSON.parse(data.jqXHR.responseText);
                                                addToLog(result, "fail");
                                            }
                                        }',
                ],
            ]); ?>

            <?php ActiveForm::end(); ?>
            <?php
            $this->registerJs("function addToLog(data, result) {"
                    ."if (result == 'fail') {"
                        ."var text = '<p style=\"color:red\">'+data.files[0].name+' - '+data.error;"
                        ."if ('log' in data) { text += '&nbsp;<a href=\"'+data.log+'\">Log</a>'}"
                        ."text += '</p>'; $('#log-info').append(text);"
                    ."} else {"
                        ."var text = '<p style=\"color:green\">'+data.files[0].name+ ' - OK';"
                        ."if ('log' in data) { text += '&nbsp;<a href=\"'+data.log+'\">Log</a>'}"
                        ."text += '</p>'; $('#log-info').append(text);"
                    ."}"
                . "}");
            ?>
        </div>
    </div>
</div>