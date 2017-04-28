<?php

use yii\db\Migration;

class m170426_120758_modify_topic_articles_table_relations extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk-topic_articles_article_id', 'topic_articles');
        $this->createIndex('index-topic_articles_article_id', 'topic_articles', 'article_id');
    }

    public function down()
    {
        echo "m170426_120758_modify_topic_articles_table_relations cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
