<?php

use yii\db\Migration;

/**
 * Handles the creation of table `favorit_article`.
 */
class m161221_151542_create_favorit_article_table extends Migration
{
    const TABLE_NAME = 'favorit_article';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'article_id' => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('FK_ArticleFavorit_UserId', self::TABLE_NAME, 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('FK_ArticleFavorit_ArticleId', self::TABLE_NAME, 'article_id', 'article', 'id', 'CASCADE');
        $this->createIndex('Uniq_ArticleFavorit', self::TABLE_NAME, ['user_id','article_id'], true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
