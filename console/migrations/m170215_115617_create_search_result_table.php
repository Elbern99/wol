<?php

use yii\db\Migration;

/**
 * Handles the creation of table `search_result`.
 */
class m170215_115617_create_search_result_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('search_result', [
            'id' => $this->primaryKey(),
            'result' => $this->getDb()->getSchema()->createColumnSchemaBuilder('MEDIUMBLOB NULL'),
            'creteria' => $this->getDb()->getSchema()->createColumnSchemaBuilder('BLOB NULL'),
            'filters' => $this->getDb()->getSchema()->createColumnSchemaBuilder('BLOB NULL'),
            'created_at' => $this->integer()->notNull()
        ]);
        
        $this->createIndex('Search_Result-Date-Index', 'search_result', 'created_at');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('search_result');
    }
}
