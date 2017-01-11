<?php

use yii\db\Migration;

/**
 * Handles the creation of table `topic_articles`.
 */
class m170109_151343_create_topic_articles_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
         $this->createTable('topic_articles', [
            'id' => $this->primaryKey(),
            'topic_id' => $this->integer()->notNull(),
            'article_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-topic_articles_topic_id', 'topic_articles', 'topic_id', 'topics', 'id', 'CASCADE');
        $this->addForeignKey('fk-topic_articles_article_id', 'topic_articles', 'article_id', 'article', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('topic_articles');
    }
}
