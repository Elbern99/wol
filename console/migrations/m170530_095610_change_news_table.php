<?php

use yii\db\Migration;

class m170530_095610_change_news_table extends Migration
{
    const TABLE_NAME = 'news';
    
    public function up() {
        $this->dropColumn(self::TABLE_NAME, 'editor');
        $this->addColumn(self::TABLE_NAME, 'sources', 'BLOB NULL');
    }

    public function down() {
        $this->dropColumn(self::TABLE_NAME, 'sources');
        $this->addColumn(self::TABLE_NAME, 'editor', $this->text()->null());
    }
}
