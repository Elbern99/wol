<?php

use yii\db\Migration;

class m160926_131408_cms_page_info_fix extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk-cms_pages-cms_page_info', 'cms_page_info');
        $this->createIndex('page_id', 'cms_page_info', 'page_id', true);
        $this->addForeignKey('fk-cms_pages-cms_page_info', 'cms_page_info', 'page_id', 'cms_pages', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-cms_pages-cms_page_info', 'cms_page_info');
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
