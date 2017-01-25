<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
//use Yii;

$this->registerJsFile('/js/pages/signup.js', ['depends'=>['yii\web\YiiAsset']]);
?>

<form action="">

    <div class="my-account-page">
        <div class="account-head-tabs">
            <div class="account-head-holder">
                <div class="container">
                    <div class="breadcrumbs">
                        <ul class="breadcrumbs-list">
                            <li><a href="">home</a></li>
                            <li>contact us</li>
                        </ul>
                    </div>

                    <div class="account-head">
                        <div class="img">
                            <div class="inner">
                                <input type="file" name="name">
                                <img src="https://codepo8.github.io/canvas-images-and-pixels/img/horse.png" alt="">
                            </div>
                            <div class="text">change photo</div>
                            <div class="icon-photo"></div>
                        </div>
                        <h1>Steve Smith</h1>
                    </div>
                </div>
            </div>
            <div class="account-tabs">
                <div class="container">
                    <a href="" class="account-delete">delete account</a>
                    <ul class="account-tabs-list">
                        <li class="active"><a href="#tab-1">Your profile</a></li>
                        <li><a href="#tab-2">Favorite articles</a></li>
                        <li><a href="#tab-3">Saved searches</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container my-account-page">
            <div class="tabs-holder">
                <!-- .preloader -->
                <div class="preloader">
                    <div class="loading-ball"></div>
                </div>
                <!-- / .preloader -->
                <div class="tabs">
                    <!-- tab -->
                    <div class="tab active" id="tab-1">
                        <div class="account-content">
                            <div class="line">
                                <div class="label-holder">Name</div>
                                <div class="desc">
                                    <div class="form-item form-item-edit">
                                        Steve Smith <a href="" class="edit">edit</a>
                                        <div class="hidden">
                                            <input type="text" name="name_edit" id="name_edit" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="line">
                                <div class="label-holder">Email address</div>
                                <div class="desc">
                                    <div class="form-item form-item-edit">
                                        steve@smith.com <a href="" class="edit">edit</a>
                                        <div class="hidden">
                                            <input type="text" name="email_edit" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="checkboxes-holder">
                                <div class="line">
                                    <div class="label-holder">Password</div>
                                    <div class="desc">
                                        <div class="form-item form-item-edit">
                                            <a href="" class="edit-password">change password</a>
                                            <div class="hidden">
                                                <input type="text" name="pass_edit" id="pass_edit" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line">
                                    <div class="label-holder">
                                        Areas of interest
                                    </div>
                                    <div class="desc">
                                        <div class="grid">
                                            <div class="grid-line one">
                                                <div class="grid-item form-item">
                                                    <div class="select-clear-all">
                                                        <span class="clear-all">Clear all</span>
                                                        <span class="select-all">Select all</span>
                                                        <div class="tooltip-dropdown dropdown left">
                                                            <div class="icon-question tooltip"></div>
                                                            <div class="tooltip-content drop-content">
                                                                <div class="icon-close"></div>
                                                                <strong>At vero eos et accusamus</strong>
                                                                Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis volupt atibus maiores alias consequatur aut per ferendis recusan ciedae doloribus asperiores.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="grid-line four">
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Program evaluation</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Behavioral and personnel economics</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Migration and ethnicity</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Labor markets and institutions</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Transition and emerging economies</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Development</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Environment</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Education and human capital</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Demography, family, and gender</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid-item">
                                                    <div class="form-item">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="name">
                                                            <span class="label-text">Data and methods</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line line-subscriptions">
                                    <div class="label-holder">Subscriptions</div>
                                    <div class="desc">
                                        <div class="form-line">
                                            <label class="def-checkbox">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Subscribe to IZA World of Labor newsletter</span>
                                            </label>
                                        </div>
                                        <div class="form-line">
                                            <label class="def-checkbox">
                                                <input type="checkbox" name="name">
                                                <span class="label-text">Subscribe to Article Alerts</span>
                                            </label>
                                        </div>
                                    </div>
                                    <a href="" class="account-delete">delete account</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- tab -->
                    <div class="tab js-tab-hidden" id="tab-2">
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
                    <!-- tab -->
                    <div class="tab js-tab-hidden" id="tab-3">
                        <table class="save-search-table">
                            <tr>
                                <th>search term</th>
                                <th>include</th>
                                <th>exclude</th>
                                <th colspan="3">content types</th>
                            </tr>
                            <tr>
                                <td>China Lehmann</td>
                                <td><span class="gray">Everything</span></td>
                                <td><span class="gray">Nothing</span></td>
                                <td><span class="gray">All</span></td>
                                <td><a href="" class="btn-blue">search</a></td>
                                <td><a href="" class="btn-border-gray-middle short"><span class="icon-trash"></span></a></td>
                            </tr>
                            <tr>
                                <td>South America</td>
                                <td>2016, Brazil</td>
                                <td>Peru, Bolivia</td>
                                <td>Development, Environment, Transition and emerging economies</td>
                                <td><a href="" class="btn-blue">search</a></td>
                                <td><a href="" class="btn-border-gray-middle short"><span class="icon-trash"></span></a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
