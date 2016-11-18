<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="cleartype" content="on"/>
    <![endif]-->
    <meta name="HandheldFriendly" content="true"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <script>
        var App = {
            initHeader: function(desktop, mobile){
                this.desktop = desktop;
                this.mobile = mobile;
            }
        };
    </script>
    
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- .preloader -->
    <div class="preloader">
        <div class="loading-ball"></div>
    </div><!-- / .preloader -->
    
    <main class="content">
        
        <div class="header-render"></div>
        
        <?php $menuDesktop = $this->renderFile('@app/views/components/header/header-desktop.php'); ?>
        <?php $menuMobile = $this->renderFile('@app/views/components/header/header-mobile.php'); ?>
        
        <script>
            var headerDesktop = '<?php echo json_encode($menuDesktop) ;?>';
            var headerMobile = '<?php echo json_encode($menuMobile) ;?>';
                App.initHeader(headerDesktop ,headerMobile );
        </script>
        
        <?php //$this->beginContent('@app/views/components/menu.php'); ?><?php //$this->endContent();?>

        <?= $content ?>
        <!--
        <div class="container">
            <?php $this->beginContent('@app/views/components/widgets.php'); ?><?php $this->endContent();?>
            
            
            <h1>For contributors</h1>
            <h2>Why contribute?</h2>

            <p>Collaboration with the IZA World of Labor gives you an opportunity to make your expertise accessible to a global non-academic audience in a cutting-edge format. Contributors can increase the availability and broaden the dissemination of highly relevant, peer-reviewed knowledge to decision-makers.</p>

            <p>Information on each article is made available to indexing and abstracting services, such as RePEc, EconLit, and Google Scholar.</p>

            <p>We do not charge authors to publish articles on IZA World of Labor.</p>
            
            <h2>How to contribute</h2>

            <p>If you would like to find out more about contributing, or to suggest a topic, please contact the IZA World of Labor Office.</p>

            <p>Before submitting a suggestion, please consider the forthcoming topics and those already published.</p>

            <p>To submit a proposal, please do so via Editorial Manager.</p>

            <p>Your proposal should come with a short abstract (three to five sentences long, no figures or tables) that outlines the content of the one-pager for your future contribution:</p>
            
            <ul>
                <li>elevator pitch—why is the topic important for our audience</li>
                <li>main pros and cons</li>
                <li>your main message</li>
            </ul>


            <p>Please select a potential subject area for your contribution when you submit a proposal. If you face any technical difficulties, please contact the IZA World of Labor Office.</p>
            
            <h2>Guidelines for authors and reviewers</h2>
            <p>IZA World of Labor articles go through a quality control system of peer review before publication. If you would like to find more specific information about the project, either as an author or a reviewer, please click the links below.</p>
            <a href="">Aims and vision</a><br>
            <a href="">Author guidelines</a><br>
            <a href="">Reviewer guidelines</a><br>
            <a href="">Style sheet</a><br>
            <a href="">Topic list</a><br>
            
            <h2>General information for contributors</h2>

            <p>Detailed information about the IZA World of Labor's policies on competing interests and principles of research integrity can be found by clicking on the links below. In addition, IZA World of Labor recognizes the importance of proper citation of data and follows the data citation convention provided below.</p>

            <a href="">FAQ on conflicts of interests</a><br>
            <a href="">IZA guiding principles of research integrity</a><br>
            <a href="">IZA World of Labor data citation convention</a><br>
        </div>
        -->
    </main>
  
  <footer class="footer">
      <div class="container">
            <div class="container-top">
                <?php $this->beginContent('@app/views/components/footer/footer.php'); ?><?php $this->endContent();?><?php $this->beginContent('@app/views/components/footer/footer-menu.php'); ?><?php $this->endContent();?>
            </div>
            <p class="copyright">
              Copyright &copy; IZA <?= date('Y') ?> <a href="#" target="_blank">Impressum</a>. All Rights Reserved. ISSN: 2054-9571
            </p>
      </div>
  </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
