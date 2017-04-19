<?php

use yii\db\Migration;

class m170411_112110_add_topics_table_is_hided_column extends Migration
{
    public function up()
    {
        $this->addColumn('topics', 'is_hided', $this->boolean()->null()->defaultValue(0));
    }

    public function down()
    {
        echo "m170411_112110_add_topics_table_is_hided_column cannot be reverted.\n";

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
