<?php

use yii\db\Migration;

class m170104_120039_add_author_fields_table extends Migration {

    const TABLE_NAME = 'author';

    public function up() {
        $this->addColumn(self::TABLE_NAME, 'url_key', $this->string()->notNull());
        $this->addColumn(self::TABLE_NAME, 'name', $this->string()->notNull());
    }

    public function down() {
        $this->dropColumn(self::TABLE_NAME, 'url_key');
        $this->dropColumn(self::TABLE_NAME, 'name');
    }

}
