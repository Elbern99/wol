<?php
namespace frontend\components\blocks;

use Yii;

class HeaderCookieNotice extends \common\modules\blocks\AbstractBlock {
    
    protected $widgetName = 'cookie_notice';
    protected $cookieName = 'cookies_notice';
    public function getView(): string {
        
        if (Yii::$app->request->cookies->get($this->cookieName) == null) {
            $widget = $this->repository->getWidget($this->widgetName);

            if (is_array($widget)) {
                return $widget['text'];
            }
        }
        
        return '';
    }
}