<?php
namespace frontend\components\blocks;

use Yii;

class StickyNewsletter extends \common\modules\blocks\AbstractBlock {
    
    protected $widgetName = 'sticky_newsletter';
    protected $cookieName = 'close_subscribse';
    
    public function getView(): string {
        //var_dump(Yii::$app->request->cookies->get($this->cookieName));exit;
        if (Yii::$app->request->cookies->get($this->cookieName) == null) {
            
            $widget = $this->repository->getWidget($this->widgetName);

            if (is_array($widget)) {
                return $widget['text'];
            }
        }
        
        return '';
    }
}