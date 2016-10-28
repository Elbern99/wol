<?php

use yii\db\Migration;

/**
 * Handles the creation of table `eav_entity`.
 */
class m161027_130709_create_eav_entity_table extends Migration
{
    const TABLE_NAME = '{{%eav_entity}}';
    
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'model_id' => $this->integer()->notNull(),
            'type_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()
        ]);
        
        $this->addForeignKey('FK_Entity_TypeId', self::TABLE_NAME, 'type_id', 'eav_entity_type', 'id');
        $this->createIndex('entity-model-index', self::TABLE_NAME, ['model_id', 'type_id'], true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
