<?php

use yii\db\Migration;

/**
 * Handles the creation of table `settings`.
 */
class m161123_091506_create_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('settings', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'name' => $this->string()->unique()->notNull(),
            'value' => $this->text()->null()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('settings');
    }
}
