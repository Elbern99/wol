<?php
use backend\components\category\CategoryManager;
use common\models\Category;

echo CategoryManager::widget([
    'query' => Category::find()->addOrderBy('root, lft'),
    'selected' => [],
    'headingOptions' => ['label' => Yii::t('app','Categories')],
    'isAdmin' => true, // optional (toggle to enable admin mode)
    'displayValue' => 1, // initial display value
    'softDelete' => false
]);
