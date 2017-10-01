<?php

use yii\helpers\Url;
?>
<ul class="abs-list">
    <?php foreach ($letters as $model): ?>
        <?php
        $class = [];

        if (!$model->stats->author_count) {
            $class[] = 'no-result';
        }

        if ($filterLetter == $model->letter) {
            $class[] = 'active';
        };

        $class = implode(' ', $class);
        ?>


        <?php if ($model->stats->author_count) : ?>
            <li class="<?= $class ?>">
                <a class="profile-author-letter" href="<?= Url::to(['authors/index', 'filter' => $model->letter]) ?>" title="<?= $model->stats->author_count; ?>">
                    <span class="text"><?= $model->letter; ?></span>
                </a>
            </li>
        <?php else : ?>
            <li class="<?= $class ?>">
                <span style="color: #999999;"><?= $model->letter; ?></span>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>