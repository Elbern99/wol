<?php

use yii\db\Migration;

class m170412_115343_create_upload_log_tacle extends Migration
{
    const TABLE_NAME = 'archive_log';
    
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'status' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'log' => $this->getDb()->getSchema()->createColumnSchemaBuilder('BLOB')->null()
        ]);
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }

}
