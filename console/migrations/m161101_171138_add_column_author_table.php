<?php

use yii\db\Migration;

class m161101_171138_add_column_author_table extends Migration
{
    public function up()
    {
        $this->addColumn('author', 'url', $this->string(50));
    }

    public function down()
    {
        $this->dropColumn('author', 'url');
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
