<?php

use yii\db\Migration;

class m170112_150711_add_topics_table_fields extends Migration
{
    public function up()
    {
        $this->addColumn('topics', 'category_id', $this->integer()->null());
    }

    public function down()
    {
        echo "m170112_150711_add_topics_table_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
