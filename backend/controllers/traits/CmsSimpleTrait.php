<?php

namespace backend\controllers\traits;

use Yii;
use common\models\CmsPagesSimple;

trait CmsSimpleTrait {
    
    public function actionSimple($page_id) {
        
        if (Yii::$app->request->isPost) {
            
            $postData = Yii::$app->request->post();
            
            if ($postData['CmsPagesSimple']['id']) {
                $model = CmsPagesSimple::find()->where(['page_id' => $page_id])->one();
            } else {
                $model = new CmsPagesSimple();
            }
            
            $model->initUploadProperty();
            
            if ($model->load($postData, '') && $model->validate()) {
                
                $model->upload();

                if ($model->save(false)) {

                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Page save success'), false);
                    Yii::$app->getSession()->setFlash('section_open', 'simple');
                }
                
            }
        }
        
        return $this->redirect(['/cms/static-pages-view', 'id' => $page_id]);
    }
    
}
    
