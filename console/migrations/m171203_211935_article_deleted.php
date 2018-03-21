<?php

use yii\db\Migration;


class m171203_211935_article_deleted extends Migration
{


    private $ad = '{{%article_deleted}}';


    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->ad, [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            ], $tableOptions);

        $this->createIndex('uniq_article_id', $this->ad, 'article_id', true);
    }


    public function safeDown()
    {
        $this->dropTable($this->ad);
    }
}
