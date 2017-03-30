<?php
namespace frontend\components\widget;

use common\contracts\SidebarWidgetInterface;
use common\models\Widget;
use yii\helpers\ArrayHelper;
use Yii;

class SidebarWidget implements SidebarWidgetInterface {
    
    private $widgets = [];
    
    public function __construct(string $page) {
        
        if (isset(Yii::$app->params['page_widget'][$page])) {
            
            $keys = Yii::$app->params['page_widget'][$page];
            $widgets = Widget::find()->where(['name' => $keys])->orderBy(['name' => SORT_DESC])->asArray()->all();
            $this->widgets = ArrayHelper::map($widgets, 'name', 'text');
        }
    }
    
    public function getPageWidgets($except = []):array {
        
        $widgets = $this->widgets;
        
        if (count($except)) {
            foreach ($except as $key) {
                ArrayHelper::remove($widgets, $key);
            }
        }
        
        return $widgets;
    }
    
    public function getPageWidget(string $name):string  {

        return $this->widgets[$name] ?? '';
    }
}

