<?php

use yii\db\Migration;

/**
 * Handles the creation for table `video`.
 */
class m161017_115741_create_video_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('video', [
            'id' => $this->primaryKey(),
            'url_key' => $this->string()->notNull()->unique(),
            'title' => $this->string()->notNull(),
            'video' => $this->string()->notNull(),
            'image' => $this->string(60)->null(),
            'description' => $this->text()->null(),
            'order' => $this->smallInteger()->null(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('video');
    }
}
