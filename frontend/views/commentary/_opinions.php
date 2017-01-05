<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>

<ul class="commentary-opinions-list">
    <?php foreach ($opinions as $opinion) : ?>
    <?php $hasImageClass = $opinion->image_link ? 'has-image' : null; ?>
    <li>
        <div class="opinion-item <?= $hasImageClass; ?>">
            <?php if ($hasImageClass) : ?>
             <a href="<?= '/opinions/'. $opinion->url_key; ?>" title="<?= $opinion->title ?>" class="img" style="background-image: url(<?= '/uploads/opinions/'.$opinion->image_link; ?>)"></a>
            <?php endif; ?>
            <div class="desc">
                <div class="inner">
                    <div class="date"><?= $opinion->created_at->format('F d, Y'); ?></div>
                    <div class="name"><a href="">Hardcoded Author</a></div>
                    <h2><?= Html::a($opinion->title, ['/opinion/view', 'slug' => $opinion->url_key]); ?></h2>
                    <?php if ($opinion->short_description) : ?>
                        <p>
                            <?= $opinion->short_description; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </li>
   <?php endforeach; ?>
</ul>
<?php if ($opinionsCount > $opinionLimit): ?>
        <?php $params = ['/commentary/opinions', 'opinion_limit' => $opinionLimit]; ?>
        <?= Html::a("show more", Url::to($params), ['class' => 'btn-gray align-center', 'id' => 'load-opinions']) ?>
<?php else: ?>
    <?php if (Yii::$app->request->get('opinion_limit')): ?>
         <?php $params = ['/commentary/opinions']; ?>
        <?= Html::a("clear", Url::to($params), ['class' => 'btn-gray align-center', 'id' => 'load-opinions']) ?>
    <?php endif; ?>
<?php endif; ?>
