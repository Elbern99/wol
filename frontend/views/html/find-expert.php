<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php
$this->title = 'Find an expert';
$this->params['breadcrumbs'][] = Html::encode($this->title);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($this->title)
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($this->title)
]);

$this->registerJsFile('/js/pages/find-expert.js', ['depends' => ['yii\web\YiiAsset']]);
?>

<div class="container find-expert">
    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
        <h1>Find an expert</h1>
        <p class="large-paragraph">With over XXX experts affiliated with IZA World of Labor, we allow access to the leading thought leaders on labor subjects across the world.</p>
        <p>If you can’t find the expert you are looking for please <a href="">get in touch.</a></p>
    </div>

    <div class="search-results-top">
        <div class="search">
            <div class="search-top">
                <span class="icon-search"></span>
                <button type="submit" class="btn-blue btn-center">
                        <span class="inner">
                            search
                        </span>
                </button>
                <div class="search-holder">
                    <input type="text" name="search" placeholder="Enter expertise or author name" class="form-control-decor">
                </div>
            </div>
        </div>
        <div class="mobile-filter-holder">
            <div class="search-results-top-filter">
                <strong>215 were found</strong>
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
        <div class="content-inner-text">
            <ul class="search-results-media-list">
                <li class="search-results-media-item">
                    <div class="img">
                        <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                    </div>
                    <div class="name">John M. Abowd</div>
                    <p class="location">Cornell University, USA, and IZA, Germany</p>
                    <p><strong>Expertise:</strong> Ethnicity, Migration</p>
                    <p><strong>Media Experience:</strong> Print, Radio</p>
                    <p><strong>Languages:</strong> English, German</p>
                    <p><strong>Country:</strong>USA</p>
                </li>
                <li class="search-results-media-item">
                    <div class="img">
                        <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                    </div>
                    <div class="name">John M. Abowd</div>
                    <p class="location">Cornell University, USA, and IZA, Germany</p>
                    <p><strong>Expertise:</strong> Ethnicity, Migration</p>
                    <p><strong>Media Experience:</strong> Print, Radio</p>
                    <p><strong>Languages:</strong> English, German</p>
                    <p><strong>Country:</strong>USA</p>
                </li>
                <li class="search-results-media-item">
                    <div class="img">
                        <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                    </div>
                    <div class="name">John M. Abowd</div>
                    <p class="location">Cornell University, USA, and IZA, Germany</p>
                    <p><strong>Expertise:</strong> Ethnicity, Migration</p>
                    <p><strong>Media Experience:</strong> Print, Radio</p>
                    <p><strong>Languages:</strong> English, German</p>
                    <p><strong>Country:</strong>USA</p>
                </li>
                <li class="search-results-media-item">
                    <div class="img">
                        <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                    </div>
                    <div class="name">John M. Abowd</div>
                    <p class="location">Cornell University, USA, and IZA, Germany</p>
                    <p><strong>Expertise:</strong> Ethnicity, Migration</p>
                    <p><strong>Media Experience:</strong> Print, Radio</p>
                    <p><strong>Languages:</strong> English, German</p>
                    <p><strong>Country:</strong>USA</p>
                </li>
                <li class="search-results-media-item">
                    <div class="img">
                        <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                    </div>
                    <div class="name">John M. Abowd</div>
                    <p class="location">Cornell University, USA, and IZA, Germany</p>
                    <p><strong>Expertise:</strong> Ethnicity, Migration</p>
                    <p><strong>Media Experience:</strong> Print, Radio</p>
                    <p><strong>Languages:</strong> English, German</p>
                    <p><strong>Country:</strong>USA</p>
                </li>
                <li class="search-results-media-item">
                    <div class="img">
                        <img src="http://images.panda.org/assets/images/pages/welcome/orangutan_1600x1000_279157.jpg" alt="">
                    </div>
                    <div class="name">John M. Abowd</div>
                    <p class="location">Cornell University, USA, and IZA, Germany</p>
                    <p><strong>Expertise:</strong> Ethnicity, Migration</p>
                    <p><strong>Media Experience:</strong> Print, Radio</p>
                    <p><strong>Languages:</strong> English, German</p>
                    <p><strong>Country:</strong>USA</p>
                </li>
            </ul>
            <a class="btn-gray align-center" href="/articles?limit=1">show more</a>
        </div>
        <aside class="sidebar-right">
            <div class="filter-clone-holder">
                <div class="filter-clone">
                    <div class="sidebar-widget sidebar-widget-filter">
                        <h3>Filter by</h3>
                        <ul class="sidebar-accrodion-list">
                            <li class="sidebar-accrodion-item is-open">
                                <a href="" class="title">country</a>
                                <div class="text">
                                    <ul class="checkbox-list">
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Articles <strong class="count">(18)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Biography<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Key Topics<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">News<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Opinions<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Events<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Videos<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">IZA policy paper<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">IZA discussion paper<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                    </ul>
                                    <a href="" class="clear-all">Clear all</a>
                                </div>
                            </li>
                            <li class="sidebar-accrodion-item">
                                <a href="" class="title">language</a>
                                <div class="text">
                                    <ul class="checkbox-list">
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Program evaluation<strong class="count">(18)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Data and methods<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Behavioral and personnel economics<strong class="count"> (2)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Migration and ethnicity<strong class="count"> (15)</strong></span>
                                            </label>
                                            <ul class="subcheckbox-list">
                                                <li>
                                                    <label class="def-checkbox light">
                                                        <input type="checkbox" name="name">
                                                        <span class="label-text">Labor mobility<strong class="count"> (15)</strong></span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="def-checkbox light">
                                                        <input type="checkbox" name="name">
                                                        <span class="label-text">Performance of migrants<strong class="count"> (5)</strong></span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="def-checkbox light">
                                                        <input type="checkbox" name="name">
                                                        <span class="label-text">Implications of migration<strong class="count"> (8)</strong></span>
                                                    </label>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Labor markets and institutions<strong class="count"> (28)</strong></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Transition and emerging economies</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Development</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name" disabled>
                                                <span class="label-text">Environment</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name" disabled>
                                                <span class="label-text">Education and human capital</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="def-checkbox light">
                                                <input type="checkbox" name="name" disabled>
                                                <span class="label-text">Demography, family, and gender</span>
                                            </label>
                                        </li>
                                    </ul>
                                    <a href="" class="clear-all">Clear all</a>
                                </div>
                            </li>
                            <li class="sidebar-accrodion-item">
                                <a href="" class="title">expertise</a>
                                <div class="text">
                                    <a href="" class="clear-all">Clear all</a>
                                </div>
                            </li>
                            <li class="sidebar-accrodion-item">
                                <a href="" class="title">media experience</a>
                                <div class="text">
                                    <a href="" class="clear-all">Clear all</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </aside>
    </div>
</div>