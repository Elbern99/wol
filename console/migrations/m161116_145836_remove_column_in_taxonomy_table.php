<?php

use yii\db\Migration;

class m161116_145836_remove_column_in_taxonomy_table extends Migration
{
    public function up()
    {
        $this->dropColumn('taxonomy', 'parent_id');
    }

}
