<?php

use yii\db\Migration;


class m171207_003919_article_created extends Migration
{


    private $ad = '{{%article_created}}';


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
