<?php

namespace backend\controllers\traits;

use Yii;
use common\models\CmsPagesSimple;
use yii\web\UploadedFile;

trait CmsSimpleTrait {
    
    public function actionSimple($page_id) {
        
        if (Yii::$app->request->isPost) {
            
            $postData = Yii::$app->request->post();
            $loadImage = false;
            if ($postData['CmsPagesSimple']['id']) {
                $model = CmsPagesSimple::find()->where(['page_id' => $page_id])->one();
            } else {
                $model = new CmsPagesSimple();
            }

            $upload = UploadedFile::getInstance($model, 'backgroud');

            if (is_object($upload) && $upload->name) {
                $postData['CmsPagesSimple']['backgroud'] = $upload;
                $loadImage = true;
            } else {
                $postData['CmsPagesSimple']['backgroud'] = $model->backgroud;
            }

            if ($model->load($postData) && $model->validate()) {
                
                if ($loadImage) {
                    $model->upload();
                }
                
                if ($model->save(false)) {

                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Page save success'), false);
                    Yii::$app->getSession()->setFlash('section_open', 'simple');
                }
                
            }
        }
        
        return $this->redirect(['/cms/static-pages-view', 'id' => $page_id]);
    }
    
}
    
