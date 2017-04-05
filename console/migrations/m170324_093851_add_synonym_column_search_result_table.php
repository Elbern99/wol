<?php

use yii\db\Migration;

class m170324_093851_add_synonym_column_search_result_table extends Migration
{
    const TABLE_NAME = 'search_result';
    
    public function up()
    {
        $this->addColumn(self::TABLE_NAME, 'synonyms', 'BLOB NULL');
    }

    public function down()
    {
        $this->dropColumn(self::TABLE_NAME, 'synonyms');
    }

}
