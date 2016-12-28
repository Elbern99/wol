<?php

use yii\db\Migration;

/**
 * Handles the creation of table `opinions`.
 */
class m161228_143812_create_opinions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('opinions', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->null(),
            'short_description' => $this->text()->null(),
            'url_key' => $this->string()->notNull()->unique(),
            'created_at' => $this->dateTime()->null(),
            'published_at' => $this->dateTime()->null(),
            'image_link' => $this->string()->null(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('opinions');
    }
}
