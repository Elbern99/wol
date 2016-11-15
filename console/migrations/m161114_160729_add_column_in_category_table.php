<?php

use yii\db\Migration;

class m161114_160729_add_column_in_category_table extends Migration
{
    public function up()
    {
        $this->addColumn('category', 'taxonomy_code', $this->string(10)->null()->unique());
    }

    public function down()
    {
        $this->dropColumn('category', 'taxonomy_code');
    }

}
