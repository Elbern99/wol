<?php

use yii\db\Migration;

/**
 * Handles the creation of table `topic_opinions`.
 */
class m170110_093003_create_topic_opinions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('topic_opinions', [
            'id' => $this->primaryKey(),
            'topic_id' => $this->integer()->notNull(),
            'opinion_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-topic_opinions_topic_id', 'topic_opinions', 'topic_id', 'topics', 'id', 'CASCADE');
        $this->addForeignKey('fk-topic_opinions_opinion_id', 'topic_opinions', 'opinion_id', 'opinions', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('topic_opinions');
    }
}
