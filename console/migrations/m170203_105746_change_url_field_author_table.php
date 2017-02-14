<?php

use yii\db\Migration;

class m170203_105746_change_url_field_author_table extends Migration
{
    public function up()
    {
        $this->alterColumn('author', 'url', $this->string(255)->null());
    }

}
