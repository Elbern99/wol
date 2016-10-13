<?php

use yii\db\Migration;

class m161013_124258_add_column_category_table extends Migration
{
    const TABLE_NAME = '{{%category}}';
    
    public function up()
    {
        $this->addColumn(self::TABLE_NAME, 'system', $this->boolean()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn(self::TABLE_NAME, 'system');
    }

}
