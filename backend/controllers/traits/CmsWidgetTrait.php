<?php

namespace backend\controllers\traits;

use Yii;
use common\models\CmsPagesWidget;
use yii\helpers\ArrayHelper;

/*
 * Extension for cms widget on page
 */
trait CmsWidgetTrait {
    
    use HelpTrait;
    
    public function actionWidget($page_id) {
            
        if (Yii::$app->request->isPost) {
            
            $widgets = CmsPagesWidget::find()->where(['page_id' => $page_id])->all();
            $current = ArrayHelper::getColumn($widgets, 'widget_id');
            $order = ArrayHelper::map($widgets, 'widget_id', 'order');

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
                        'widget_id' => $wData,
                        'order' => $post['order'][$wData] ?? 0
                    ];
                }
                unset($diff);

                $insertCount = Yii::$app->db->createCommand()
                        ->batchInsert(
                            CmsPagesWidget::tableName(), ['page_id', 'widget_id', 'order'], $bulkInsertArray
                        )
                        ->execute();

                if ($insertCount) {
                    Yii::$app->getSession()->setFlash('success', 'Widget add success', false);
                }
            }
            
            if (!empty($order)) {
                $postOrder = $post['order'];
                
                foreach ($order as $id=>$widgetOrder) {

                    if (isset($postOrder[$id]) && $postOrder[$id] != $widgetOrder) {
                        $model = CmsPagesWidget::find()->where(['widget_id' => $id, 'page_id' => $page_id])->one();
                        $model->order = $postOrder[$id];
                        $model->save();
                    }
                }
            }
            
            Yii::$app->getSession()->setFlash('section_open', 'widget');
        }
        
        return $this->redirect(['/cms/static-pages-view', 'id' => $page_id]);
    }
}
    
