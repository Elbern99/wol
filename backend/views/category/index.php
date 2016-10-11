<?php
use yii\helpers\Html;
use backend\components\category\CategoryView;
use common\models\Category;
use backend\components\category\Module;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Category');
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
                'nodeAddlViews' => [
                    Module::VIEW_PART_2 => '@backend/views/category/form_part_description'
                ],
                    //'cacheSettings'   => ['enableCache' => true]      // normally not needed to change
            ]);
            ?>
        </div>
    </div>
</div>

