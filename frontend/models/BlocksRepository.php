<?php
namespace frontend\models;

use common\modules\blocks\contracts\BlockRepositoryInterface;
use common\models\Widget;

class BlocksRepository implements BlockRepositoryInterface{
    
    public function getWidget(string $name) {
        return Widget::find()->select('text')->where(['name' => $name])->asArray()->one();
    }
}

