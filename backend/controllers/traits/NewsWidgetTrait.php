<?php

namespace backend\controllers\traits;

use Yii;
use common\models\NewsWidget;
use yii\helpers\ArrayHelper;
use common\models\Widget as ModelWidget;
/*
 * Extension for cms widget on page
 */
trait NewsWidgetTrait {
    
    use HelpTrait;
    
    public function changeWidget($news_id) {
 
        $widgets = NewsWidget::find()->where(['news_id' => $news_id])->all();
        $current = ArrayHelper::getColumn($widgets, 'widget_id');
        $order = ArrayHelper::map($widgets, 'widget_id', 'order');

        $post = Yii::$app->request->post('Widget');
        $selected = [];

        if (isset($post['id'])) {
            $selected = $post['id'];
        }

        $diff = $this->getDiff($selected, $current);

        if (!empty($diff['delete'])) {
            NewsWidget::deleteAll(['widget_id' => $diff['delete'], 'news_id' => $news_id]);
        }

        if (!empty($diff['add'])) {

            $bulkInsertArray = [];

            foreach ($diff['add'] as $wData) {

                $bulkInsertArray[] = [
                    'news_id' => $news_id,
                    'widget_id' => $wData,
                    'order' => $post['order'][$wData] ?? 0
                ];
            }
            unset($diff);

            $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                        NewsWidget::tableName(), ['news_id', 'widget_id', 'order'], $bulkInsertArray
                    )
                    ->execute();

            if ($insertCount) {
                Yii::$app->getSession()->setFlash('success', 'Widgets has been updated', false);
            }
        }

        if (!empty($order)) {
            $postOrder = $post['order'];

            foreach ($order as $id=>$widgetOrder) {

                if (isset($postOrder[$id]) && $postOrder[$id] != $widgetOrder) {
                    $model = NewsWidget::find()->where(['widget_id' => $id, 'news_id' => $news_id])->one();
                    $model->order = $postOrder[$id];
                    $model->save();
                }
            }
        }
        
    }
    
    protected function getNewsWidget($id) {
        
        if (!$id) {
            return false;
        }
        
        $items = ArrayHelper::map(ModelWidget::find()->select(['id', 'name'])->all(), 'id', 'name');
        $model = new ModelWidget();

        $selected = NewsWidget::find()->where(['news_id' => $id])->all();
        $model->id = ArrayHelper::getColumn($selected, 'widget_id');
        $model->order = ArrayHelper::map($selected, 'widget_id', 'order');

        return ['items' => $items, 'model' => $model, 'news_id' => $id];
    }

}
    
