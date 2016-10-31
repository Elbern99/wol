<?php

use yii\db\Migration;

class m161031_113242_create_eav_type_table extends Migration
{
    const TABLE_NAME = '{{%eav_type}}';
    
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->unique(),
        ]);
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
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
