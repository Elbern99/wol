<?php use yii\helpers\Url; ?>
<div class="tab js-tab-hidden" id="tab-2">
    
    <?php foreach ($articles as $article): ?>
    <a href="<?= Url::to(['/my-account/remove-favorite', 'id' => $article->id]) ?>"><div class="icon-close"></div></a>
    <?php endforeach; ?>
    
    <ul class="favourite-articles-list">
        <li class="article-item">
            <div class="icon-close"></div>
            <ul class="article-rubrics-list">
                <li><a href="/subject-areas/performance-of-migrants">Performance of migrants</a></li>
                <li><a href="/subject-areas/migration-policy">Migration policy</a></li>
            </ul>
            <h2><a href="/articles/what-are-consequences-of-regularizing-undocumented-immigrants">What are the consequences of regularizing undocumented immigrants?</a></h2>
            <h3>When countries regularize undocumented
                residents, their work, wages, and human capital investment opportunities
                change</h3>
            <div class="publish"><a href="">Sherrie A. Kossoudji</a>, September 2016</div>
            <div class="description">
                Millions of people enter (or remain in)
                countries without permission as they flee violence, war, or economic
                hardship. Regularization policies that offer residence and work rights have
                multiple and multi-layered effects on the economy and society, but they
                always directly affect the labor market opportunities of those who are
                regularized. Large numbers of undocumented people in many countries, a new
                political willingness to fight for human and civil rights, and dramatically
                increasing refugee flows mean continued pressure to enact regularization
                policies.
            </div>
            <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
        </li>
    </ul>
</div>