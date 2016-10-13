<?php

use yii\db\Migration;

class m161013_131022_change_column_category_table extends Migration
{
    
    const TABLE_NAME = '{{%category}}';
    
    public function up()
    {
        $this->alterColumn(self::TABLE_NAME, 'type', 'SMALLINT(5) null');
    }

    public function down()
    {
        $this->alterColumn(self::TABLE_NAME, 'type', 'SMALLINT(5) not null');
    }
}
