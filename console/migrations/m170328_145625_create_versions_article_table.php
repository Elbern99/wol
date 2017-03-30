<?php

use yii\db\Migration;

/**
 * Handles the creation of table `versions_article`.
 */
class m170328_145625_create_versions_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('versions_article', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'version_number' => $this->integer(20)->notNull(),
            'sort_key' => $this->string()->notNull(),
            'seo' => $this->string()->notNull()->unique(),
            'doi' => $this->string(50)->notNull(),
            'availability' => $this->string(50),
            'enabled' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
            'publisher' => $this->string(50)->null(),
            'title' => $this->string()->notNull(),
            'notices' => $this->getDb()->getSchema()->createColumnSchemaBuilder('BLOB')->null(),
        ]);
        
        $this->createIndex('versions_article-article_id-index', 'versions_article', 'article_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('versions_article');
    }
}
