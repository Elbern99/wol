<?php

use yii\db\Migration;

class m161101_164354_rename_author_column_table extends Migration
{
    public function up()
    {
        $this->renameColumn('author', 'enable', 'enabled');
    }

    public function down()
    {
        $this->renameColumn('author', 'enabled', 'enable');
    }

}
