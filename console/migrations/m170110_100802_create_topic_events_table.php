<?php

use yii\db\Migration;

/**
 * Handles the creation of table `topic_events`.
 */
class m170110_100802_create_topic_events_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('topic_events', [
            'id' => $this->primaryKey(),
            'topic_id' => $this->integer()->notNull(),
            'event_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-topic_events_topic_id', 'topic_events', 'topic_id', 'topics', 'id', 'CASCADE');
        $this->addForeignKey('fk-topic_events_event_id', 'topic_events', 'event_id', 'events', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('topic_events');
    }
}
