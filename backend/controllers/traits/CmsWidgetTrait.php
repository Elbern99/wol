<?php

namespace backend\controllers\traits;

use Yii;
use common\models\CmsPagesWidget;
use yii\helpers\ArrayHelper;

trait CmsWidgetTrait {
    
    public function actionWidget($page_id) {
            
        if (Yii::$app->request->isPost) {
            
            $current = ArrayHelper::getColumn(CmsPagesWidget::find()->where(['page_id' => $page_id])->all(), 'widget_id');
            $post = Yii::$app->request->post('Widget');
            
            $selected = [];
            
            if (isset($post['id'])) {
                $selected = $post['id'];
            }
            $diff = $this->getDiff($selected, $current);
            
            if (!empty($diff['delete'])) {
                CmsPagesWidget::deleteAll(['widget_id' => $diff['delete'], 'page_id' => $page_id]);
            }

            if (!empty($diff['add'])) {

                $bulkInsertArray = [];

                foreach ($diff['add'] as $wData) {

                    $bulkInsertArray[] = [
                        'page_id' => $page_id,
                        'widget_id' => $wData
                    ];
                }
                unset($diff);

                $insertCount = Yii::$app->db->createCommand()
                        ->batchInsert(
                                CmsPagesWidget::tableName(), ['page_id', 'widget_id'], $bulkInsertArray
                        )
                        ->execute();

                if ($insertCount) {
                    Yii::$app->getSession()->setFlash('success', 'Widget add success', false);
                }
            }
            Yii::$app->getSession()->setFlash('section_open', 'widget');
        }
        
        return $this->redirect(['/cms/static-pages-view', 'id' => $page_id]);
    }
    
    protected function getDiff($selected, $current) {

        if (empty($selected) && !empty($current)) {     
            
            $diff['delete'] = $current;
            $diff['add'] = [];
        } elseif (empty($current) && !empty($selected)) {   
            
            $diff['delete'] = [];
            $diff['add'] = $selected;
        } elseif (!empty($selected) && !empty($current)) {
            
            $diff['delete'] = [];
            
            foreach ($current as $id) {
                if (array_search($id, $selected) === false) {
                    $diff['delete'][] = $id;
                }
            }

            $diff['add'] = array_diff(array_merge($diff['delete'], $selected), $current);
        } else {
            
            $diff['delete'] = [];
            $diff['add'] = [];
        }

        return $diff;
    }
}
    
