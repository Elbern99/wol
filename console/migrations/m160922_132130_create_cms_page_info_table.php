<?php

use yii\db\Migration;

/**
 * Handles the creation for table `cms_page_info`.
 */
class m160922_132130_create_cms_page_info_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cms_page_info', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'meta_title' => $this->string()->notNull(),
            'meta_keywords' => $this->text()->null(),
            'meta_description' => $this->text()->null()
        ]);
        
        $this->addForeignKey('fk-cms_pages-cms_page_info', 'cms_page_info', 'page_id', 'cms_pages', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-cms_pages-cms_page_info', 'cms_page_info');
        $this->dropTable('cms_page_info');
    }
}
