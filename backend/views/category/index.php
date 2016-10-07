<?php
use yii\helpers\Html;
use backend\components\category\CategoryView;
use common\models\Category;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Category';//Yii::t('app.user', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row content">
        <div class="col-sm-12 sidenav">
            <?php
            echo CategoryView::widget([
                // single query fetch to render the tree
                'query' => Category::find()->addOrderBy('root, lft'),
                'headingOptions' => ['label' => 'Categories'],
                'isAdmin' => true, // optional (toggle to enable admin mode)
                'displayValue' => 1, // initial display value
                'softDelete'      => false,
                'showIDAttribute' => false,
                    //'cacheSettings'   => ['enableCache' => true]      // normally not needed to change
            ]);
            ?>
        </div>
    </div>
</div>

