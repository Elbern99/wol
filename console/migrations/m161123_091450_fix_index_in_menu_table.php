<?php

use yii\db\Migration;

class m161123_091450_fix_index_in_menu_table extends Migration
{
    public function up()
    {
        $this->dropIndex('type-enabled-index', 'menu_links');
        $this->createIndex('enabled-index', 'menu_links', 'enabled');
    }

}
