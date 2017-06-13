<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news_widget`.
 */
class m170601_140744_create_news_widget_table extends Migration
{
    const TABLE_NAME = 'news_widget';
    
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'widget_id' => $this->integer()->notNull(),
            'order' => $this->smallInteger()->defaultValue(0)
        ]);
        
        $this->addForeignKey('fk-news-news_widget', self::TABLE_NAME, 'news_id', 'news', 'id', 'CASCADE');
        $this->addForeignKey('fk-widget-news_widget', self::TABLE_NAME, 'widget_id', 'widget', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
