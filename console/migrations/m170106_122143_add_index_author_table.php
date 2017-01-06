<?php

use yii\db\Migration;

class m170106_122143_add_index_author_table extends Migration
{
    const TABLE_NAME = 'author';
    
    public function up() {
        $this->createIndex('Author-UrlKey-Index-Uniq', self::TABLE_NAME, 'url_key', true);
        $this->createIndex('Author-Name-Index', self::TABLE_NAME, 'name');
    }

    public function down() {
        $this->dropIndex('Author-UrlKey-Index-Uniq', self::TABLE_NAME);
        $this->dropIndex('Author-Name-Index', self::TABLE_NAME);
    }

}
