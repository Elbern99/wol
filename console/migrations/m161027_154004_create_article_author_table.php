<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_author`.
 */
class m161027_154004_create_article_author_table extends Migration
{
    const TABLE_NAME = '{{%article_author}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('FK_ArticleAuthor_ArticleId', self::TABLE_NAME, 'article_id', 'article', 'id', 'CASCADE');
        $this->addForeignKey('FK_ArticleAuthor_AuthorId', self::TABLE_NAME, 'author_id', 'author', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
