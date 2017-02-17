<?php

use yii\db\Migration;

class m170216_100104_alter_events_table extends Migration
{
    public function up()
    {
        $this->alterColumn('events', 'contact_link', $this->string(255)->null());
    }

    public function down()
    {
        echo "m170216_100104_alter_events_table cannot be reverted.\n";

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
