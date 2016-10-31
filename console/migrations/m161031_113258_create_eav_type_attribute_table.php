<?php

use yii\db\Migration;

class m161031_113258_create_eav_type_attribute_table extends Migration
{
    const TABLE_NAME = '{{%eav_type_attribute}}';
    
    public function up()
    {
      $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull(),
            'attribute_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('FK_EntityTypeAttribute_AttributeId', self::TABLE_NAME, 'attribute_id', 'eav_attribute', 'id', 'CASCADE');
        $this->addForeignKey('FK_EntityTypeAttribute_TypeId', self::TABLE_NAME, 'type_id', 'eav_type', 'id', 'CASCADE');
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
