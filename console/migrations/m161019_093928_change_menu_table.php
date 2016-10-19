<?php

use yii\db\Migration;

class m161019_093928_change_menu_table extends Migration {

    const TABLE_NAME = '{{%menu_links}}';
    
    public function up() {
        $this->renameTable('bottom_menu', self::TABLE_NAME);
        $this->addColumn(self::TABLE_NAME, 'type', $this->smallInteger(5)->notNull());
    }

    public function down() {
        $this->renameTable(self::TABLE_NAME, 'bottom_menu');
        $this->dropColumn(self::TABLE_NAME, 'type');
    }

}
