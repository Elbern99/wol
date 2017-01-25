<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news_articles`.
 */
class m170124_120049_create_news_articles_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('news_articles', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'article_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-news_articles_news_id', 'news_articles', 'news_id', 'news', 'id', 'CASCADE');
        $this->addForeignKey('fk-news_articles_article_id', 'news_articles', 'article_id', 'article', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('news_articles');
    }
}
