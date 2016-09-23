<?php

use yii\db\Migration;

class m160923_155741_add_field_to_user_table extends Migration
{
    const TABLE_NAME = 'user';
    
    public function up()
    {
        $this->addColumn(self::TABLE_NAME, 'is_admin', $this->boolean()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn(self::TABLE_NAME, 'is_admin');
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
