<?php
use common\models\BottomMenu;
use frontend\components\BottomMenuWidget;

echo BottomMenuWidget::widget(['query' => BottomMenu::getVisibleItemsQuery(), 'options'=>['id'=>'bottom-menu']]);
?>
<p class="pull-left">&copy; IZA World of Labor<?= date('Y') ?></p>

