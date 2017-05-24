<?php

use yii\db\Migration;

class m170523_095754_change_opinion_author_table extends Migration
{
    const TABLE_NAME = 'opinion_authors';
    
    public function up()
    {
        $this->dropForeignKey('fk-opinion_authors_author_id', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'author_id');
        
        $this->addColumn(self::TABLE_NAME, 'author_name', $this->string()->null());
        $this->addColumn(self::TABLE_NAME, 'author_url', $this->string()->null());
        $this->addColumn(self::TABLE_NAME, 'author_order', $this->smallInteger(20)->defaultValue(0));
    }

}
