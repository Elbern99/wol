<?php

use yii\db\Migration;

/**
 * Handles the creation of table `topics`.
 */
class m170106_095426_create_topics_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('topics', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'url_key' => $this->string()->notNull()->unique(),
            'description' => $this->text()->null(),
            'short_description' => $this->text()->null(),
            'image_link' => $this->string()->null(),
            'created_at' => $this->dateTime()->null(),
            'is_key_topic' => $this->boolean()->null(),
            'sticky_at' => $this->dateTime()->null(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('topics');
    }
}
