<?php
use frontend\components\search\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Result;
?>

<?php
$this->title = 'Search Result For ';
$this->params['breadcrumbs'][] = Html::encode($this->title);

$this->registerJsFile('/js/plugins/mark.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/jquery.mark.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/pages/advanced-search.js', ['depends' => ['yii\web\YiiAsset']]);

?>

<div class="container search-results">
    <div class="breadcrumbs">
        <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
    </div>
    <h1>Search the site</h1>

    <form action="#">
        <div class="search-results-top">
            <div class="search">
                <a href="" class="btn-border-blue-large with-icon-r btn-save-search">
                    <span class="icon-save"></span>
                    <div class="btn-save-search-inner">search saved to your account</div>
                </a>
                <div class="search-bottom">
                    <a href="">advanced search</a>
                </div>
                <div class="search-top">
                    <span class="icon-search"></span>
                    <button type="submit" class="btn-blue btn-center">
                        <span class="inner">
                            search
                        </span>
                    </button>
                    <div class="search-holder">
                        <input type="search" name="search" placeholder="Keyword(s) or name" class="form-control-decor">
                    </div>
                </div>
            </div>
            <div class="search-results-top-text">
                Your search for <strong>China Lehmann</strong> returned <strong><?=$resultCount?></strong> results <a href="" class="refine-link">Refine</a>
            </div>
            <div class="mobile-filter-holder">
                <div class="search-results-top-filter">
                    <strong><?=$resultCount?> results</strong>
                    <a href="" class="filter-mobile-link">Filter</a>
                    <a href="" class="refine-mobile-link">Refine</a>
                </div>
                <div class="mobile-filter">
                    <div class="mobile-filter-container"></div>
                    <div class="mobile-filter-bottom">
                        <button type="submit" class="btn-blue-large">update</button>
                        <a href="" class="btn-no-style">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-inner">
            <div class="content-inner-text contact-page">
                <ul class="search-results-media-list">
                    <li class="search-results-media-item">
                        <div class="img">
                            <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                        </div>
                        <div class="link"><a href="">TOPIC</a></div>
                        <div class="name">The Chinese economy</div>
                    </li>
                    <li class="search-results-media-item">
                        <div class="img">
                            <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                        </div>
                        <div class="link"><a href="">AUTHOR</a></div>
                        <div class="name">Hartmut Lehmann</div>
                        <p>Professor of Economic Policy in the Department of Economics at the University of Bologna, Italy</p>
                    </li>
                </ul>

                <div class="search-results-table">
                    <div class="search-results-table-top">
                        <div class="search-results-item">
                            <div class="publication-date">
                                Publication date
                            </div>
                            <div class="type">
                                Type
                            </div>
                            <div class="description-center">
                                Description
                            </div>
                        </div>
                    </div>

                    <div class="search-results-table-body">
                        <?php foreach ($resultData as $result): ?>

                            <?php 
                                switch ($result['type']) {
                                    case 'article':
                                        if(isset(Result::$value[$result['type']][$result['id']])) {
                                           echo $this->render('items/article.php',['value' => Result::$value[$result['type']][$result['id']]]);
                                        }
                                    break;
                                }
                            ?>
                        
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="pagination">
                    <div class="pagination-select">
                        <div class="label-text">show</div>
                        <label class="custom-select-def">
                            <select name="show-count">
                                <option value="">10</option>
                                <option value="">25</option>
                                <option selected value="">50</option>
                                <option value="">100</option>
                            </select>
                        </label>
                    </div>
                    
                    <?= LinkPager::widget([
                            'pagination' => $paginate, 
                            'options' => ['class' => 'pagination-list'], 
                            'nextPageLabel' => 'Next', 
                            'prevPageLabel' => 'Previous',
                            'lastPageLabel' => true,
                            'maxButtonCount' => 9
                        ]); 
                    ?>
                </div>
            </div>

            <aside class="sidebar-right">
                
            </aside>
        </div>
    </form>
</div>


