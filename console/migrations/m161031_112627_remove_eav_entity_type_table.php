<?php

use yii\db\Migration;

class m161031_112627_remove_eav_entity_type_table extends Migration
{
    const TABLE_NAME = '{{%eav_entity_type}}';
     
    public function up()
    {
        $this->dropForeignKey('FK_Entity_TypeId', 'eav_entity');
        $this->dropTable(self::TABLE_NAME);
    }

    public function down()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->unique(),
            'attribute_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('FK_EntityType_AttributeId', self::TABLE_NAME, 'attribute_id', 'eav_attribute', 'id', 'CASCADE');
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
