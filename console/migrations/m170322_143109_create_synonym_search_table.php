<?php

use yii\db\Migration;

class m170322_143109_create_synonym_search_table extends Migration
{
    
    const TABLE_NAME = 'synonyms_search';
    
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'synonyms' => $this->text()->notNull()
        ]);
    }

    public function down()
    {
       $this->dropTable(self::TABLE_NAME);
    }
}
