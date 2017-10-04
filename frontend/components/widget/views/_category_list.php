<?php
if (empty($level)) {
    $level = 0;
}
?>

<ul<?php if ($level === 0 && !empty($cssClass)) :?> class="<?= $cssClass; ?>"<?php endif;?> >
<?php foreach ($items as $category) : ?>
    <?= $this->render('_category_list_item', ['category' => $category, 'level' => $level+1]); ?>
<?php endforeach; ?>
</ul>