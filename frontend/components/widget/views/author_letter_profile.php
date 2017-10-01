<?php

use yii\helpers\Url;
?>
<ul class="abs-list">
    <?php foreach ($letters as $model): ?>
        <?php if ($model->stats->author_count) : ?>
            <?php $route = $type ? ['authors/letter', 'type' => $type] : ['authors/letter']; ?>
            <li>
                <a class="profile-author-letter" href="<?= Url::to($route) ?>" title="<?= $model->stats->author_count; ?>" data-letter="<?= $model->letter; ?>">
                    <span class="text"><?= $model->letter; ?></span>
                </a>
            </li>
        <?php else : ?>
            <li>
                <span style="color: #999999;"><?= $model->letter; ?></span>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
<div class="author-letter-result"></div>