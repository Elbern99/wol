<?php

use yii\db\Migration;

class m161115_162332_alter_taxonomy_code_field extends Migration
{
    public function up()
    {
        $this->alterColumn('category', 'taxonomy_code', $this->string(50)->null());
    }

    public function down()
    {

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
