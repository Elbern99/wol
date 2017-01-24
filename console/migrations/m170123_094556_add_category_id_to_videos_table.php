<?php

use yii\db\Migration;

class m170123_094556_add_category_id_to_videos_table extends Migration
{
    public function up()
    {
        $this->addColumn('video', 'category_id', $this->integer()->null());
    }

    public function down()
    {
        echo "m170123_094556_add_category_id_to_videos_table cannot be reverted.\n";

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
