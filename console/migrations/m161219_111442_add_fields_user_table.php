<?php

use yii\db\Migration;

class m161219_111442_add_fields_user_table extends Migration
{
    const TABLE_NAME = 'user';
    
    public function up()
    {
        $this->addColumn(self::TABLE_NAME, 'first_name', $this->string()->notNull());
        $this->addColumn(self::TABLE_NAME, 'last_name', $this->string()->notNull());
        $this->addColumn(self::TABLE_NAME, 'avatar', $this->string()->null());
        $this->alterColumn(self::TABLE_NAME, 'username', $this->string()->null());
    }

    public function down()
    {
        $this->dropColumn(self::TABLE_NAME, 'first_name');
        $this->dropColumn(self::TABLE_NAME, 'last_name');
        $this->dropColumn(self::TABLE_NAME, 'avatar');
        $this->alterColumn(self::TABLE_NAME, 'username', $this->string()->notNull());
    }

}
