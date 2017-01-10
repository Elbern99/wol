<?php

use yii\db\Migration;

/**
 * Handles the creation of table `topic_videos`.
 */
class m170110_090853_create_topic_videos_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('topic_videos', [
            'id' => $this->primaryKey(),
            'topic_id' => $this->integer()->notNull(),
            'video_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-topic_videos_topic_id', 'topic_videos', 'topic_id', 'topics', 'id', 'CASCADE');
        $this->addForeignKey('fk-topic_videos_video_id', 'topic_videos', 'video_id', 'video', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('topic_videos');
    }
}
