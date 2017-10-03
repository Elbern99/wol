<?php

use yii\helpers\Url;
?>
<ul class="abs-list">
    <?php foreach ($letters as $model): ?>
        <?php if ($model->$relation->author_count) : ?>
            <?php $route = $type ? ['authors/letter', 'type' => $type] : ['authors/letter']; ?>
            <li>
                <a class="profile-author-letter" href="<?= Url::to($route) ?>" title="<?= $model->$relation->author_count; ?>" data-letter="<?= $model->letter; ?>">
                    <span class="letter"><?= $model->letter; ?></span>
                </a>
            </li>
        <?php else : ?>
            <li>
                <span class="letter" style="color: #999999;"><?= $model->letter; ?></span>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
<div class="author-letter-result"></div>