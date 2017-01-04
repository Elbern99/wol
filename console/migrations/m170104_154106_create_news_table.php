<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news`.
 */
class m170104_154106_create_news_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'url_key' => $this->string()->notNull()->unique(),
            'editor' => $this->string()->notNull(),
            'description' => $this->text()->null(),
            'short_description' => $this->text()->null(),
            'image_link' => $this->string()->null(),
            'created_at' => $this->dateTime()->null(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('news');
    }
}
