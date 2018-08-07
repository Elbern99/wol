<?php

use yii\db\Migration;


/**
 * Class m180805_224108_drop_uniq_doi_for_create_control
 */
class m180805_224108_drop_uniq_doi_for_create_control extends Migration
{


    private $ad = '{{%article_created}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('uniq_doi_control', $this->ad);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createIndex('uniq_doi_control', $this->ad, 'doi_control');
    }
}
