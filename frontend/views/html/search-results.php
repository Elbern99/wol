<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
$this->registerJsFile('/js/plugins/mark.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/plugins/jquery.mark.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('/js/pages/advanced-search.js', ['depends' => ['yii\web\YiiAsset']]);
?>

<div class="container search-results">
    <div class="breadcrumbs">
        <ul class="breadcrumbs-list">
            <li><a href="">home</a></li>
            <li>Frequently asked questions and help</li>
        </ul>
    </div>
    <h1>Search the site</h1>

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
                <form action="url">
                    <span class="icon-search"></span>
                    <button type="submit" class="btn-blue btn-center">
                        <span class="inner">
                            search
                        </span>
                    </button>
                    <div class="search-holder">
                        <input type="search" name="search" placeholder="Keyword(s) or name" class="form-control-decor">
                    </div>
                </form>
            </div>
        </div>
        <div class="search-results-top-text">
            Your search for <strong>China Lehmann</strong> returned <strong>215</strong> results <a href="" class="refine-link">Refine</a>
        </div>

        <div class="mobile-filter-holder">
            <div class="search-results-top-filter">
                <strong>215 results</strong>
                <a href="" class="filter-mobile-link">Filter</a>
                <a href="" class="refine-mobile-link">Refine</a>
            </div>
            <div class="mobile-filter">
                <div class="mobile-filter-container">

                </div>
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
                    <div class="search-results-item">
                        <div class="publication-date">
                            May 2014
                        </div>
                        <div class="type">
                            Article
                        </div>
                        <div class="description-center">
                            <h2><a href="">Worker displacement in transition economies and in China</a></h2>
                            <h3>Knowing which workers are displaced in restructuring episodes helps governments devise the right equity- and efficiency-enhancing policies</h3>
                            <div class="name"><a href="">Hartmut Lehmann</a> <span class="date-in-name">,May 2014</span></div>
                            <div class="description">
                                Since the 1990s, South Korea’s population has been aging and its fertility rate has fallen. At the same time, the number of Koreans living abroad has risen considerably. These trends threaten to diminish South Korea’s international and economic stature. To mitigate the negative effects of these new challenges, South Korea has begun to engage the seven million Koreans living abroad, transforming the diaspora into a positive force for long-term development.
                            </div>
                            <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                        </div>
                    </div>
                    <div class="search-results-item">
                        <div class="publication-date">
                            May 2014
                        </div>
                        <div class="type">
                            Article
                        </div>
                        <div class="description-center">
                            <h2><a href="">China's Overt Economic Rise and Latent Human Capital Investment: Achieving Milestones and Competing for the Top</a><a href="" class="icon-link"></a></h2>
                            <h3>Knowing which workers are displaced in restructuring episodes helps governments devise the right equity- and efficiency-enhancing policies</h3>
                            <div class="name"><a href="">Hartmut Lehmann</a> <span class="date-in-name">,May 2014</span></div>
                            <div class="description">
                                Since the 1990s, South Korea’s population has been aging and its fertility rate has fallen. At the same time, the number of Koreans living abroad has risen considerably. These trends threaten to diminish South Korea’s international and economic stature. To mitigate the negative effects of these new challenges, South Korea has begun to engage the seven million Koreans living abroad, transforming the diaspora into a positive force for long-term development.
                            </div>
                            <a href="" class="article-more"><span class="more">More</span><span class="less">Less</span></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pagination">
                <div class="pagination-select">
                    <div class="label-text">show</div>
                    <label class="custom-select-def">
                        <select name="show-count">
                            <option value="">1</option>
                            <option value="">2</option>
                            <option value="">3</option>
                            <option value="">40</option>
                        </select>
                    </label>
                </div>

                <ul class="pagination-list">
                    <li class="disabled prev"><a href="">Previous</a></li>
                    <li class="active"><a href="">1</a></li>
                    <li><a href="">2</a></li>
                    <li><a href="">3</a></li>
                    <li><a href="">4</a></li>
                    <li><a href="">5</a></li>
                    <li><a href="">6</a></li>
                    <li><a href="">7</a></li>
                    <li><a href="">8</a></li>
                    <li><a href="">9</a></li>
                    <li>...</li>
                    <li class="all"><a href="">100</a></li>
                    <li class="next"><a href="">Next</a></li>
                </ul>
            </div>
        </div>

        <aside class="sidebar-right">
            <div class="sidebar-widget sidebar-widget-save">
                <a href="" class="btn-border-blue-large with-icon btn-save-search">
                    <span class="inner">
                        <span class="icon-save"></span>save search
                    </span>
                    <div class="btn-save-search-inner">search saved to your account</div>
                </a>
            </div>

            <div class="filter-clone">
                <div class="sidebar-widget sidebar-widget-sort-by">
                    <label>sort by</label>
                    <div class="custom-select dropdown">
                        <div class="custom-select-title dropdown-link">
                            Publication date (descending)
                        </div>
                        <div class="sort-list drop-content">
                            <div>
                                <a href="/articles">Publication date (descending)</a>
                            </div>
                            <div>
                                <a href="/articles?sort=1">Publication date (ascending)</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sidebar-widget sidebar-widget-filter">
                    <h3>Filter results by</h3>
                    <ul class="sidebar-accrodion-list">
                        <li class="sidebar-accrodion-item is-open">
                            <a href="" class="title">content types <strong>(39)</strong></a>
                            <div class="text">
                                <ul class="checkbox-list">
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Articles <strong class="count">(18)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Biography<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Key Topics<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">News<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Opinions<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Events<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Videos<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">IZA policy paper<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">IZA discussion paper<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                </ul>
                                <a href="" class="clear-all">Clear all</a>
                            </div>
                        </li>
                        <li class="sidebar-accrodion-item">
                            <a href="" class="title">subject areas <strong>(43)</strong></a>
                            <div class="text">
                                <ul class="checkbox-list">
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Program evaluation<strong class="count">(18)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Data and methods<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Behavioral and personnel economics<strong class="count"> (2)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Migration and ethnicity<strong class="count"> (15)</strong></span>
                                        </label>
                                        <ul class="subcheckbox-list">
                                            <li>
                                                <label class="def-checkbox light">
                                                    <input type="checkbox" name="">
                                                    <span class="label-text">Labor mobility<strong class="count"> (15)</strong></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="def-checkbox light">
                                                    <input type="checkbox" name="">
                                                    <span class="label-text">Performance of migrants<strong class="count"> (5)</strong></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="def-checkbox light">
                                                    <input type="checkbox" name="">
                                                    <span class="label-text">Implications of migration<strong class="count"> (8)</strong></span>
                                                </label>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Labor markets and institutions<strong class="count"> (28)</strong></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Transition and emerging economies</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="">
                                            <span class="label-text">Development</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="" disabled>
                                            <span class="label-text">Environment</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="" disabled>
                                            <span class="label-text">Education and human capital</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="def-checkbox light">
                                            <input type="checkbox" name="" disabled>
                                            <span class="label-text">Demography, family, and gender</span>
                                        </label>
                                    </li>
                                </ul>
                                <a href="" class="clear-all">Clear all</a>
                            </div>
                        </li>
                        <li class="sidebar-accrodion-item">
                            <a href="" class="title">key topics <strong>(39)</strong></a>
                            <div class="text">
                            </div>
                            <a href="" class="clear-all">Clear all</a>
                        </li>
                        <li class="sidebar-accrodion-item">
                            <a href="" class="title">authors <strong>(39)</strong></a>
                            <div class="text">
                            </div>
                            <a href="" class="clear-all">Clear all</a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</div>