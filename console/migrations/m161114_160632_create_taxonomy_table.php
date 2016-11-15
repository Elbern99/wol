<?php

use yii\db\Migration;

/**
 * Handles the creation of table `taxonomy`.
 */
class m161114_160632_create_taxonomy_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('taxonomy', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull()->unique(),
            'parent_id' => $this->integer()->null(),
            'value' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->null()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('taxonomy');
    }
}
