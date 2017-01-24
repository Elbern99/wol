<?php

use yii\db\Migration;

class m170124_101422_add_column_newletter_table extends Migration
{
    public function up()
    {
        $this->addColumn('newsletter', 'code', $this->string()->null());
        $this->createIndex('Newsletter-Code-Index', 'newsletter', 'code');
    }
}
