<?php

use yii\db\Migration;


/**
 * Class m180802_235641_add_revision_description
 */
class m180802_235641_add_revision_description extends Migration
{


    private $a = '{{%article}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->a, 'revision_description', $this->string()->null()->defaultValue(null));
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->a, 'revision_description');
    }
}
