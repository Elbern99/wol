<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m161027_153941_create_article_category_table extends Migration
{
    const TABLE_NAME = '{{%article_category}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'category_id' => $this->bigInteger(20)->notNull()
        ]);
        
        $this->addForeignKey('FK_ArticleCategroy_ArticleId', self::TABLE_NAME, 'article_id', 'article', 'id', 'CASCADE');
        $this->addForeignKey('FK_ArticleCategroy_CategoryId', self::TABLE_NAME, 'category_id', 'category', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
