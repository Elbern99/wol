<?php

use yii\db\Migration;

class m160926_131349_cms_pages_fix extends Migration
{
    public function up()
    {
        $this->alterColumn('cms_pages', 'url', 'varchar(255) null');
    }

    public function down()
    {
        $this->alterColumn('cms_pages', 'url', 'varchar(255) not null');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
