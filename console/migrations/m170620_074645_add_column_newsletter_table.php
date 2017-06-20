<?php

use yii\db\Migration;

class m170620_074645_add_column_newsletter_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('newsletter', 'user_id', $this->integer()->null());
        $this->addForeignKey('fk-newsletter-user', 'newsletter', 'user_id', 'user', 'id', 'SET NULL');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-newsletter-user', 'newsletter');
        $this->dropColumn('newsletter', 'user_id');
    }
}
