<?php

use yii\db\Migration;

class m170704_124619_add_index_in_tables extends Migration
{
    public function safeUp()
    {
        $this->createIndex('news-enabled-index', 'news', 'enabled');
        $this->createIndex('events-enabled-index', 'events', 'enabled');
        $this->createIndex('news-opinions-index', 'opinions', 'enabled');
    }
}
