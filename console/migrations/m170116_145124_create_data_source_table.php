<?php

use yii\db\Migration;

/**
 * Handles the creation of table `data_source`.
 */
class m170116_145124_create_data_source_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('data_source', [
            'id' => $this->primaryKey(),
            'source' => $this->string()->notNull(),
            'website' => $this->string()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('data_source');
    }
}
