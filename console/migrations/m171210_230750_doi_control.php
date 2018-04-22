<?php

use yii\db\Migration;


class m171210_230750_doi_control extends Migration
{


    private $ad = '{{%article_created}}';


    public function safeUp()
    {
        $this->addColumn($this->ad, 'doi_control', $this->string(50)->notNull());
        $this->createIndex('uniq_doi_control', $this->ad, 'doi_control');
    }


    public function safeDown()
    {
        $this->dropColumn($this->ad, 'doi_control');
    }
}
