<?php
use common\models\MenuLinks;
use frontend\components\MenuWidget;

echo MenuWidget::widget(['query' => MenuLinks::getVisibleItemsQuery(1), 'options'=>['id'=>'top-menu']]);
