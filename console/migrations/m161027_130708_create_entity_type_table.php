<?php

use yii\db\Migration;

/**
 * Handles the creation of table `entity_type`.
 */
class m161027_130708_create_entity_type_table extends Migration
{
    const TABLE_NAME = '{{%eav_entity_type}}';
    
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->unique(),
            'attribute_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('FK_EntityType_AttributeId', self::TABLE_NAME, 'attribute_id', 'eav_attribute', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
