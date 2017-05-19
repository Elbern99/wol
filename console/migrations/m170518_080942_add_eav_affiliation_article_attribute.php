<?php

use yii\db\Migration;

class m170518_080942_add_eav_affiliation_article_attribute extends Migration
{
    public function up()
    {
        Yii::$app->runAction('eav/add-attribute', ['affiliation_article', 'Affiliation', 0, 0, 1]);
        Yii::$app->runAction('eav/add-attribute-option', ['affiliation_article', 'Affiliation', 'Array']);
        Yii::$app->runAction('eav/add-attribute-type', ['affiliation_article', 'article']);
    }

    public function down()
    {
    }

}
