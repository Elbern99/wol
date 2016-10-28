<?php

use yii\db\Migration;

/**
 * Handles the creation of table `attribute_option`.
 */
class m161027_142324_create_attribute_option_table extends Migration
{
    
    const TABLE_NAME = '{{%eav_attribute_option}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'attribute_id' => $this->integer()->notNull(),
            'label' => $this->string()->notNull(),
            'type' => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('FK_Option_AttributeId', self::TABLE_NAME, 'attribute_id', 'eav_attribute', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
