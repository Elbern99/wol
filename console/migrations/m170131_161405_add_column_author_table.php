<?php

use yii\db\Migration;

class m170131_161405_add_column_author_table extends Migration
{
    public function up()
    {
        $this->addColumn('author', 'surname', $this->string(80)->null());
        $this->createIndex('Author-Surname-Index', 'author', 'surname');
    }

    public function down()
    {
        $this->dropColumn('author', 'surname');
    }

}
