<?php
namespace common\helpers;

use common\contracts\VideoInterface;
/* https://github.com/oscarotero/Embed */
use Embed\Embed;

class VideoHelper {
    
    static $model;
    static $data = null;
    
    public static function load(VideoInterface $model) {
        self::$model = $model;
    }
    
    private static function getInstance() {
        
        if (is_null(self::$data) && is_object(self::$model)) {
            self::$data = Embed::create(self::$model->getVideo());
        }
        
        return self::$data;
    }
    
    public static function getImage() {
        
        $video = self::getInstance();
        
        if (is_object($video)) {
            return $video->image;
        }
        
        return '';
    }
}

