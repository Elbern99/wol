<?php

use yii\db\Migration;

class m170612_114118_add_column_search_result_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('search_result', 'ip', $this->char(15)->null());
    }

    public function safeDown()
    {
        $this->dropColumn('search_result', 'ip');
    }
}
