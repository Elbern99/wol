<?php

use yii\db\Migration;

/**
 * Handles the creation for table `cms_page_sections`.
 */
class m160922_135124_create_cms_page_sections_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cms_page_sections', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'anchor' => $this->string()->notNull(),
            'open' => $this->boolean()->defaultValue(0),
            'order' => $this->smallInteger()->notNull(),
            'text' => $this->text()->notNull(),
        ]);

        $this->addForeignKey('fk-cms_pages-cms_page_sections', 'cms_page_sections', 'page_id', 'cms_pages', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-cms_pages-cms_page_sections', 'cms_page_sections');
        $this->dropTable('cms_page_sections');
    }
}
