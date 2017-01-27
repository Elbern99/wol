<?php

use yii\db\Migration;

/**
 * Handles the creation of table `press_releases`.
 */
class m170126_134327_create_press_releases_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('press_releases', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'url_key' => $this->string()->notNull()->unique(),
            'description' => $this->text()->null(),
            'short_description' => $this->text()->null(),
            'image_link' => $this->string()->null(),
            'pdf_link' => $this->string()->null(),
            'created_at' => $this->dateTime()->null(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('press_releases');
    }
}
