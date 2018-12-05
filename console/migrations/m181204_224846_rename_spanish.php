<?php

use yii\db\Migration;


/**
 * Class m181204_224846_rename_spanish
 */
class m181204_224846_rename_spanish extends Migration
{


    private $lang = '{{%lang}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update($this->lang, ['name' => 'Español'], ['code' => 'es']);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "No action needed to step down.\n";

        return true;
    }
}
