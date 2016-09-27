<?php

use yii\db\Migration;

/**
 * Handles the creation for table `cms_pages`.
 */
class m160922_125730_create_cms_pages_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cms_pages', [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'enabled' => $this->boolean()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cms_pages');
    }
}
