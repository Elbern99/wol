<?php
namespace common\modules\category;
use common\components\Type;
use Yii;

class CategoryType extends Type {
    
    const ARTICLE_CATEGORY_TYPE = 1;
    const TOPIC_CATEGORY_TYPE = 2;
    
    private $params;
    
    public function __construct() {
        
        $types = [
            self::ARTICLE_CATEGORY_TYPE => 'article',
            self::TOPIC_CATEGORY_TYPE => 'topic'
        ];
        
        $this->addTypes($types);
        $this->params = Yii::$app->params['category_type_class'] ?? [];
    }
    
    public function getClassById($key) {
        
        $name = $this->getTypeByKey($key);
        return $this->params[$name] ?? null;
    }
}

