<?php
namespace frontend\components\widget;

use Yii;
use yii\helpers\Html;

class CustomGridView extends \yii\grid\GridView {
    
    public $previosLetter = null;
    
    public function renderItems()
    {
        $cache = Yii::$app->cache;
        $variation = 0;

        if (isset(Yii::$app->request->get('SourcesSearch')["sourceTaxonomies"])) {
            $variation = Yii::$app->request->get('SourcesSearch')["sourceTaxonomies"];
        }

        $key = 'data-source-items-'.$variation;
        
        $data = $cache->get($key);
        
        if ($data) {
            return $data;
        }
        
        $caption = $this->renderCaption();
        $columnGroup = $this->renderColumnGroup();
        $tableHeader = $this->showHeader ? $this->renderTableHeader() : false;
        $tableBody = $this->renderTableBody();
        $tableFooter = $this->showFooter ? $this->renderTableFooter() : false;
        $content = array_filter([
            $caption,
            $columnGroup,
            $tableHeader,
            $tableFooter,
            $tableBody,
        ]);
        
        $renderContent = Html::tag('table', implode("\n", $content), $this->tableOptions);
        $cache->set($key, $renderContent, 86400);
        
        return $renderContent;
    }
    
}