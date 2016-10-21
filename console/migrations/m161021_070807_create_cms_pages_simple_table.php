<?php

use yii\db\Migration;

/**
 * Handles the creation for table `cms_pages_simple`.
 */
class m161021_070807_create_cms_pages_simple_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cms_pages_simple', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->unique()->notNull(),
            'backgroud' => $this->string()->null(),
            'text' => $this->text()->null()
        ]);
        
        $this->addForeignKey('fk-cms_pages-cms_pages_simple', 'cms_pages_simple', 'page_id', 'cms_pages', 'id', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cms_pages_simple');
    }
}
