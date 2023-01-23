<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
$this->registerJsFile('/js/plugins/scrollpane.js', ['depends' => ['yii\web\YiiAsset']]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="content-type" content="text/html; charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="cleartype" content="on"/>
    <![endif]-->
    <meta name="HandheldFriendly" content="true"/>
    <?= Html::csrfMetaTags() ?>
    <title id="title-document"><?= Html::encode($this->title) ?></title>
    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon/manifest.json">
<!--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">-->
<!--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">-->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <script type="text/javascript" async defer src="https://apis.google.com/js/platform.js?publisherid=116017394173863766515"></script>
    <?php $this->head() ?>
    <script type="application/javascript">
        function acceptGA() {
            <!-- Google Tag Manager -->
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-5K3G23');
            <!-- End Google Tag Manager -->
        }

        function declineGA() {

        }
    </script>
</head>
<body>
<?php $this->beginBody() ?>

<?php //= $this->renderFile('@app/views/components/cookie-notice.php'); ?>

<div id="cookie-bar" style="display: none; position: fixed; inset: 0px; background-color: rgba(0, 170, 238, 0.3); z-index: 9000;">
    <div style="display: flex; height: 100%; width: 100%; justify-items: center; align-items: center;">
        <div class="container col-10 col-sm-6 col-md-6 col-lg-5 col-xl-4" style="background-color: white;">
            <div class="row">
                <div id="cookiebar" style="display: block">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-12 mt-2" style="font-size: 16px !important; color: black !important;">
                                We use cookies to provide you with an optimal website experience. This includes cookies that are necessary for the operation of the site as well as cookies that are only used for anonymous statistical purposes, for comfort settings or to display personalized content. You can decide for yourself which categories you want to allow. Please note that based on your settings, you may not be able to use all of the site's functions.                            </div>

                            <div class="col-12 mt-3 mb-2" style="margin-top: 1rem!important; margin-bottom: 0.5rem!important;">
                                <button type="button" class="btn btn-dark btn-block" data-toggle="modal" data-target="#configureCookieConsent" tabindex="1" style="color: #fff; background-color: #343a40; border-color: #343a40; display: block; width: 100%;">Configure consent</button>
                            </div>

                            <div class="col-12 mb-2" style="margin-bottom: 0.5rem!important;">
                                <button id="acceptAllbtn" type="button" class="btn btn-dark btn-block" onclick="acceptAll()" tabindex="2" style="color: #fff; background-color: #343a40; border-color: #343a40; display: block; width: 100%;">Accept all</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" data-keyboard="false" data-backdrop="static" id="configureCookieConsent" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h5 class="modal-title" id="modalTitle" style="font-size: 16px">Cookie settings</h5>
                        </div>

                        <div class="modal-body">
                            <div class="form-check" id="necessary">
                                <input class="form-check-input" checked="" disabled="" type="checkbox" id="necessaryCheck">
                                <span class="checkbox-icon-wrapper">
                                    <svg class="svg-inline--fa fa-check fa-w-16 checkbox-icon fa-fw" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg><!-- <span class="checkbox-icon fas fa-fw fa-check"></span> Font Awesome fontawesome.com -->
                                </span>
                                <label class="form-check-label" for="necessaryCheck" style="font-size: 16px !important; color: black;">
                                    Necessary
                                </label>
                            </div>
                            <p>These necessary cookies are required to activate the core functionality of the website. An opt-out from these technologies is not available.</p>
                            <div class="collapse-box">
                                <div class="collapse-group">
                                    <div class="collapse-group">
                                        <a href="#grecaptcha" aria-controls="collapseLaravel" data-toggle="collapse" aria-expanded="false">
                                            <span style="font-size: 16px;">_GRECAPTCHA</span>
                                        </a>
                                        <div id="grecaptcha" class="collapse">
                                            <div class="card-block">
                                                This is a functional cookie, which protects our site from spam enquiries on  subscription and registration forms. Duration - session</div>
                                        </div>
                                    </div>
                                    <a href="#csrfFrontend" aria-controls="collapseCB" data-toggle="collapse" aria-expanded="false">
                                        <span style="font-size: 16px;">_csrf-frontend</span>
                                    </a>
                                    <div id="csrfFrontend" class="collapse">
                                        <div class="card-block">
                                            Protection from csrf attacks. Contains a token that stores encoded information about user. This cookie is destroyed after browser is closed</div>
                                    </div>
                                </div>

                                <div class="collapse-group">
                                    <a href="#closeSubscribe" aria-controls="collapseXsrf" data-toggle="collapse" aria-expanded="false">
                                        <span style="font-size: 16px;">close_subscribe</span>
                                    </a>
                                    <div id="closeSubscribe" class="collapse">
                                        <div class="card-block">
                                            Contains information that user will not see a news subscription popup. Expirey: One month                                       </div>
                                    </div>
                                </div>

                                <div class="collapse-group">
                                    <a href="#cookiesNotice" aria-controls="collapseXsrf" data-toggle="collapse" aria-expanded="false">
                                        <span style="font-size: 16px;">cookies_notice</span>
                                    </a>
                                    <div id="cookiesNotice" class="collapse">
                                        <div class="card-block">
                                            Contains information about cookies on this website, including web analysis cookies. Expirey: One month                                       </div>
                                    </div>
                                </div>

                                <div class="collapse-group">
                                    <a href="#advancedFrontend" aria-controls="collapseXsrf" data-toggle="collapse" aria-expanded="false">
                                        <span style="font-size: 16px;">advanced-frontend</span>
                                    </a>
                                    <div id="advancedFrontend" class="collapse">
                                        <div class="card-block">
                                            Internal cookie which is used to define user in system, login functional in user’s account. Expires after current browser session</div>
                                    </div>
                                </div>

                                <div class="collapse-group">
                                    <a href="#identityFrontend" aria-controls="collapseXsrf" data-toggle="collapse" aria-expanded="false">
                                        <span style="font-size: 16px;">_identity-frontend</span>
                                    </a>
                                    <div id="identityFrontend" class="collapse">
                                        <div class="card-block">
                                            Internal cookie which is used for currently logged user to identify user in system. Expirey: One month</div>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-4">
                            <div class="form-check">
                                <input id="analytics" class="form-check-input" type="checkbox">
                                <span class="checkbox-icon-wrapper">
                                    <svg class="svg-inline--fa fa-check fa-w-16 checkbox-icon fa-fw" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg><!-- <span class="checkbox-icon fas fa-fw fa-check"></span> Font Awesome fontawesome.com -->
                                </span>
                                <label class="form-check-label" for="analytics" style="font-size: 16px !important; color: black;">
                                    Analytics
                                </label>
                            </div>
                            <p>In order to further improve our offer and our website, we collect anonymous data for statistics and analyses. With the help of these cookies we can, for example, determine the number of visitors and the effect of certain pages on our website and optimize our content.</p>
                            <div class="collapse-box">
                                <div class="collapse-group">
                                    <a href="#ga" aria-controls="collapseGa" data-toggle="collapse" aria-expanded="false">
                                        <span style="font-size: 16px;">ga</span>
                                    </a>
                                    <div id="ga" class="collapse">
                                        <div class="card-block">
                                            Google Analytics                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="link-group" style="font-size: 16px !important;">
                                <!--<a href="./imprint">Imprint</a> |--> <a href="./privacy-and-cookie-policy">Privacy Policy</a>
                            </div>

                            <button type="button" class="btn" data-dismiss="modal" onclick="acceptDialog()" style="color: #3c3c3b;
    display: inline-block;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;">
                                Accept
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="holder">

<div class="overlay js-tab-hidden"></div>

<div class="wrapper">

    <main class="content">
        <?= $this->renderFile('@app/views/components/header/desktop/header.php'); ?>
        <?= $this->renderFile('@app/views/components/header/mobile/header.php'); ?>

        <?= $content ?>
        
        <?php $this->beginContent('@app/views/components/widgets.php'); ?><?php $this->endContent();?>

    </main>
  
  <footer class="footer">
      <div class="footer-inner">
          <div class="container">
              <div class="container-top">
                  <?= $this->renderFile('@app/views/components/footer/footer.php'); ?>
              </div>
              <p class="copyright">
                  Copyright &copy; IZA <?= date('Y') ?> <a href="https://www.iza.org/imprint" target="_blank">Impressum</a>. <br>All Rights Reserved. ISSN: 2054-9571
              </p>
          </div>
      </div>
  </footer>

<!--    <div class="alert alert-primary fade in" id="asking">
        <button type="button" class="close remember-alert" data-dismiss="alert" aria-hidden="true">×</button>
        <p>
            How can we improve IZA World of Labor?
            Click <a target="_blank" onclick="window.open('https://forms.office.com/r/Q13ZijPare', '_blank');" href="#">here</a> to take our 5-minute survey and enter a prize draw.
        </p>
    </div> -->
</div>

<?php $this->endBody() ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

<script type="application/javascript">
    $(function () {
        let cookieState = Cookies.get('cb-enabled');
        if (cookieState !== 'accepted') {
            $('body').addClass('cookieSticky');
            $('#cookie-bar').css('display', 'block');
        }
        let analyticsState = Cookies.get('ga-analytics')
        if(analyticsState === 'accepted'){
            acceptGA()
        }else{
            declineGA()
        }
    })

    function acceptDialog() {
        if ($('#analytics').prop('checked')) {
            acceptAll();
        } else {
            acceptNecessary()
        }
    }

    function acceptAll() {
        $('body').removeClass('cookieSticky');
        $('#cookie-bar').css('display', 'none');
        Cookies.set('cb-enabled', 'accepted', {expires: 365});
        Cookies.set('ga-analytics', 'accepted', {expires: 365});
        acceptGA()
    }

    function acceptNecessary() {
        $('body').removeClass('cookieSticky');
        $('#cookie-bar').css('display', 'none');
        Cookies.set('cb-enabled', 'accepted', {expires: 365});
        Cookies.set('ga-analytics', 'declined', {expires: 365});
        declineGA()
    }
</script>

</body>
</html>
<?php $this->endPage() ?>
