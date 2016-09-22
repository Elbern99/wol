<?php

use yii\db\Migration;

/**
 * Handles the creation for table `bottom_menu`.
 */
class m160914_131827_create_bottom_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('bottom_menu', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'link' => $this->string()->null(),
            'class' => $this->string()->null(),
            'order' => $this->smallInteger()->notNull(),
            'enabled' => $this->boolean()->defaultValue(0)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('bottom_menu');
    }
}
