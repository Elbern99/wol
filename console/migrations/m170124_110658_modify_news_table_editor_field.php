<?php

use yii\db\Migration;

class m170124_110658_modify_news_table_editor_field extends Migration
{
    public function up()
    {
        $this->alterColumn('news', 'editor', 'text not null');
    }

    public function down()
    {
        echo "m170124_110658_modify_news_table_editor_field cannot be reverted.\n";

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
