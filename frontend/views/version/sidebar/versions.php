<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="sidebar-widget sidebar-widget-version">
    <div class="sidebar-widget-version-item">
        <div class="widget-title">Versions</div>
        <div class="number">
            <?php if ($article->notices): ?>
                <?php 
                $stack = new SplStack();
                $stack->unserialize($article->notices);
                $cnt = $stack->count();
                ?>
                <div class="icon-exclamatory-circle tooltip">
                    <div class="tooltip-content">
                        <?php while($cnt > 0): ?>
                            <?php echo $stack->pop(); $cnt--; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif; ?>
            Previous version: <strong><?= $article->version_number ?></strong>
        </div>
        <div class="date">
            <div class="title">date</div>
            <?= date('F Y', $article->created_at) ?>
        </div>
        <div class="doi">
            <div class="title">DOI</div>
            <a href="http://dx.doi.org/<?= $article->doi ?>" target="_blank"><?= $article->doi ?></a>
        </div>
        
        <div class="authors">
            <div class="title">author(s)</div>
            <?php if(count($authorsList)): ?>
                <?php foreach($authorsList as $authorAttribute): ?>
                    <?= Html::a($authorAttribute['name'], $authorAttribute['url']) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="article-number">Article number: <strong><?= $article['article']->id ?></strong></div>
    </div>
    <div class="sidebar-widget-version-item">
        <div class="number">
            <?php if ($article['article']->notices): ?>
                <?php
                $stack = new SplStack();
                $stack->unserialize($article['article']->notices);
                $cnt = $stack->count();
                ?>
                <div class="icon-exclamatory-circle tooltip">
                    <div class="tooltip-content">
                        <?php while($cnt > 0): ?>
                            <?php echo $stack->pop(); $cnt--; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif; ?>
            <a href="<?= Url::to('/articles/'.$article['article']->seo) ?>">Current version</a>
        </div>
        <?php $versions = $article->getRelatedVersions(); ?>
        <?php if (count($versions)): ?>
            <?php foreach($versions as $version): ?>
                <div class="number">
                    <?php if (isset($version['notices'])): ?>
                        <?php
                        $stack = new SplStack();
                        $stack->unserialize($version['notices']);
                        $cnt = $stack->count();
                        ?>
                        <div class="icon-exclamatory-circle tooltip">
                            <div class="tooltip-content">
                                <?php while($cnt > 0): ?>
                                    <?php echo $stack->pop(); $cnt--; ?>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <a href="<?= Url::to('/articles/'.$version['seo']) ?>">version: <strong><?= $version['version_number'] ?></strong></a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>