<?php

use yii\db\Migration;

class m161019_105941_add_index_menu_table extends Migration {

    const TABLE_NAME = '{{%menu_links}}';
    
    public function up() {
        $this->createIndex('type-enabled-index', self::TABLE_NAME, ['type', 'enabled']);
    }

    public function down() {
        $this->dropIndex('type-enabled-index', self::TABLE_NAME);
    }

}
