<?php

use yii\db\Migration;

/**
 * Handles the creation of table `events`.
 */
class m161226_151121_create_events_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('events', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'url_key' => $this->string()->notNull()->unique(),
            'date_from' => $this->dateTime()->null(),
            'date_to' => $this->dateTime()->null(),
            'location' => $this->string()->notNull(),
            'body' => $this->text()->null(),
            //New fields
            'short_description' => $this->text()->null(),
            'book_link' => $this->string()->notNull(),
            'contact_link' => $this->string()->notNull(),
            'image_link' => $this->string()->null(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('events');
    }
}
