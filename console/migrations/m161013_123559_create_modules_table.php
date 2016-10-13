<?php

use yii\db\Migration;

/**
 * Handles the creation for table `modules`.
 */
class m161013_123559_create_modules_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('modules', [
            'id' => $this->primaryKey(),
            'key' => $this->string(40)->notNull()->unique(),
            'title' => $this->string(50)->notNull(),
            'system' => $this->boolean()->defaultValue(1)
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('modules');
    }
}
