<?php

use yii\db\Migration;

/**
 * Handles the creation of table `value`.
 */
class m161027_134918_create_value_table extends Migration
{
    const TABLE_NAME = '{{%eav_value}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer()->notNull(),
            'attribute_id' => $this->integer()->notNull(),
            'lang_id' => $this->integer()->defaultValue(0),
            'value' => $this->text()->null()
        ]);
        
        $this->addForeignKey('FK_Value_EntityId', self::TABLE_NAME, 'entity_id', 'eav_entity', 'id', 'CASCADE');
        $this->addForeignKey('FK_Value_AttributeId', self::TABLE_NAME, 'attribute_id', 'eav_attribute', 'id', 'CASCADE');
        
        $this->createIndex('INDEX-entity-lang', self::TABLE_NAME, ['entity_id', 'lang_id']);
        //$this->createIndex('INDEX-entity-attribute', self::TABLE_NAME, ['entity_id', 'attribute_id']);
        //$this->createIndex('INDEX-entity-attribute-lang', self::TABLE_NAME, ['entity_id', 'attribute_id', 'lang_id']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
