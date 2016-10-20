<?php

use yii\db\Migration;

/**
 * Handles the creation for table `cms_pages_widget`.
 */
class m161020_115452_create_cms_pages_widget_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cms_pages_widget', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'widget_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-cms_pages-cms_pages_widget', 'cms_pages_widget', 'page_id', 'cms_pages', 'id', 'CASCADE');
        $this->addForeignKey('fk-widget-cms_pages_widget', 'cms_pages_widget', 'widget_id', 'widget', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cms_pages_widget');
    }
}
