<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="sidebar-widget sidebar-widget-version">
    <div class="sidebar-widget-version-item">
        <div class="widget-title" style="text-transform: none;">Version(s)</div>
        <div class="number">
            <?php if ($article->notices): ?>
                <?php
                $stack = new SplStack();
                $stack->unserialize($article->notices);
                $cnt = $stack->count();
                ?>
                <div class="icon-exclamatory-circle tooltip">
                    <div class="tooltip-content">
                        <?php while ($cnt > 0): ?>
                            <?php
                            echo $stack->pop();
                            $cnt--;
                            ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($article->allVersions): ?>
            <?php foreach ($article->allVersions as $version): ?>
                <div class="number">
                    <?php if ($version->id == $article->id) : ?>
                        <?php if ($version->is_current) : ?>Current <?php endif ?>Version: <strong><?= $article->version; ?></strong> 
                    <?php else : ?>
                        <a href="<?= $version->urlOnePager ?>"><?php if ($version->is_current) : ?>Current <?php endif ?>Version: <strong><?= $version->version ?></strong></a>
                    <?php endif ?>
                    <?php if ($version->revision_description) : ?>
                        <div class="icon-exclamatory-circle tooltip">
                            <div class="tooltip-content">
                                <?= $version->revision_description; ?>
                            </div>
                        </div>
                    <?php endif ?>
                </div>

            <?php endforeach; ?>
        <?php endif ?>        

        <div class="date">
            <div class="title">date</div>
            <?= date('F Y', $article->created_at) ?>
        </div>
        <div class="doi">
            <div class="title">DOI</div>
            <a href="http://dx.doi.org/<?= $article->fullDoi ?>" target="_blank"><?= $article->fullDoi ?></a>
        </div>

        <div class="authors">
            <div class="title">Author<?= count($article->authorList) > 1 ? 's' : ''; ?></div>
            <?php if (count($authorsList)): ?>
                <?php foreach ($authorsList as $author): ?>
                    <div class="author-item"><a href="<?= $author['url'] ?>"><?= $author['name']; ?></a></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="article-number">Article number: <strong><?= $article->article_number ?></strong></div>
    </div>
</div>
