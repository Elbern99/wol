<?php

use yii\db\Migration;

class m161031_114741_change_type_option_table extends Migration
{
    const TABLE_NAME = '{{%eav_attribute_option}}';
    
    public function up()
    {
        $this->alterColumn(self::TABLE_NAME, 'type', $this->string(20)->notNull());
    }

    public function down()
    {
        $this->alterColumn(self::TABLE_NAME, 'type', $this->integer()->notNull());
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
