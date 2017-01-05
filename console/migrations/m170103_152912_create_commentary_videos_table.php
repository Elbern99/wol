<?php

use yii\db\Migration;

/**
 * Handles the creation of table `commentary_videos`.
 */
class m170103_152912_create_commentary_videos_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('commentary_videos', [
            'id' => $this->primaryKey(),
            'video_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-commentary_videos_video_id', 'commentary_videos', 'video_id', 'video', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('commentary_videos');
    }
}
