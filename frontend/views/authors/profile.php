<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Author;
?>

<?php
$this->title = $author['author']->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.menu', 'Authors'), 'url' => Url::toRoute(['/authors'])];
$this->params['breadcrumbs'][] = Html::encode($this->title);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($this->title)
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($this->title)
]);
?>

<div class="container find-expert">
    <div class="article-head">
        <div class="breadcrumbs">
            <?php $this->beginContent('@app/views/components/breadcrumbs.php'); ?><?php $this->endContent(); ?>
        </div>
    </div>

    <div class="search-results-top">
           
    </div>
</div>
