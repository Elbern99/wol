<?php
use yii\helpers\Url;
?>

<li class="classification-list-item level-<?= $level; ?>">
    <a href="<?= Url::to([$category['url_key']]) ?>"><?= $category['title'] ?></a>
    <?php if (!empty($category['items'])) :?>
    <?= $this->render('_category_list', ['items' => $category['items'], 'level' => $level]);?>
    <?php endif; ?>
</li>