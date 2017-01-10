<?php

use yii\db\Migration;

class m170110_083014_add_newsletter_feed_table extends Migration
{
    const TABLE_NAME = 'newsletter_news';
    
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'url_key' => $this->string()->notNull()->unique(),
            'date' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'main' => $this->text()->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }

}
