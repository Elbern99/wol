<?php

use yii\db\Migration;


/**
 * Class m181023_235637_add_spanish_lang
 */
class m181023_235637_add_spanish_lang extends Migration
{


    private $lang = '{{%lang}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert($this->lang, [
            'code' => 'es',
            'name' => 'Spanish',
            'image' => '/images/lang/spain.jpg',
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->db->createCommand('DELETE FROM ' . $this->lang . ' WHERE code="es"')->execute();
    }
}
