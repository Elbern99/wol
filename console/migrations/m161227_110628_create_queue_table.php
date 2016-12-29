<?php

use yii\db\Migration;

/**
 * Handles the creation of table `queue`.
 */
class m161227_110628_create_queue_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('queue', [
            'id' => $this->bigPrimaryKey()->notNull(),
            'status' => 'TINYINT NOT NULL DEFAULT 0',
            'timestamp' => 'DATETIME NOT NULL',
            'data' => 'LONGBLOB',
        ]);
        
        $this->createIndex('QueueIndex', 'queue', 'status');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('queue');
    }
}
