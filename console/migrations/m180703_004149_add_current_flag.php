<?php

use yii\db\Migration;


/**
 * Class m180703_004149_add_current_flag
 */
class m180703_004149_add_current_flag extends Migration
{


    private $a = '{{%article}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->a, 'is_current', $this->boolean());
        $this->db->createCommand('update ' . $this->a . ' set is_current=1')->execute();
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180703_004149_add_current_flag cannot be reverted.\n";

        return false;

        //$this->dropColumn($this->a, 'is_current');
    }
}
