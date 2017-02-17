<?php
use yii\helpers\Url;
use frontend\components\articles\OrderBehavior;

$sort = Yii::$app->request->get('sort');
?>
<label>sort by</label>
<div class="custom-select dropdown">
    <div class="custom-select-title dropdown-link">
        Relevance
    </div>
    <div class="sort-list drop-content">
        <div>
            <a href="<?= Url::to(array_merge($currentUrl, ['sort' => 'relevance'])) ?>">Relevance</a>
        </div>
        <div <?= (!$sort && !is_null($sort)) ? 'data-select="selected"' : '' ?>>
            <a href="<?= Url::to(array_merge($currentUrl, ['sort' => OrderBehavior::DATE_DESC])) ?>">Publication date (descending)</a>
        </div>
        <div <?= ($sort == OrderBehavior::DATE_ASC) ? 'data-select="selected"' : '' ?>>
            <a href="<?= Url::to(array_merge($currentUrl, ['sort' => OrderBehavior::DATE_ASC])) ?>">Publication date (ascending)</a>
        </div>
        <div <?= ($sort == OrderBehavior::AUTHOR_ASC) ? 'data-select="selected"' : '' ?>>
            <a href="<?= Url::to(array_merge($currentUrl, ['sort' => OrderBehavior::AUTHOR_ASC])) ?>">Author last name (A to Z)</a>
        </div>
        <div <?= ($sort == OrderBehavior::AUTHOR_DESC) ? 'data-select="selected"' : '' ?>>
            <a href="<?= Url::to(array_merge($currentUrl, ['sort' => OrderBehavior::AUTHOR_DESC])) ?>">Author last name (Z to A)</a>
        </div>
        <div <?= ($sort == OrderBehavior::TITLE_ASC) ? 'data-select="selected"' : '' ?>>
            <a href="<?= Url::to(array_merge($currentUrl, ['sort' => OrderBehavior::TITLE_ASC])) ?>">Title (A to Z)</a>
        </div>
        <div <?= ($sort == OrderBehavior::TITLE_DESC) ? 'data-select="selected"' : '' ?>>
            <a href="<?= Url::to(array_merge($currentUrl, ['sort' => OrderBehavior::TITLE_DESC])) ?>">Title (Z to A)</a>
        </div>
    </div>
</div>
