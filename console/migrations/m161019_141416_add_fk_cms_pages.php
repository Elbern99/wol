<?php

use yii\db\Migration;

class m161019_141416_add_fk_cms_pages extends Migration {

    const TABLE_NAME = '{{%cms_pages}}';
    
    public function up() {
        
        $this->addColumn(self::TABLE_NAME, 'modules_id', $this->integer()->notNull());
        $this->addForeignKey('fk-cms_pages-modules', self::TABLE_NAME, 'modules_id', 'modules', 'id');
    }

    public function down() {
        
        $this->dropForeignKey('fk-cms_pages-modules', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'modules_id');
    }

}
