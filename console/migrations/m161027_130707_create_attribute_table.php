<?php

use yii\db\Migration;

/**
 * Handles the creation of table `attribute`.
 */
class m161027_130707_create_attribute_table extends Migration
{
    const TABLE_NAME = '{{%eav_attribute}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'label' => $this->string()->notNull(),
            'in_search' => $this->boolean()->defaultValue(0),
            'required' => $this->boolean()->defaultValue(0),
            'enabled' => $this->boolean()->defaultValue(1),
        ]);
        
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
