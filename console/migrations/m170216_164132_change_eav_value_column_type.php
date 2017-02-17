<?php

use yii\db\Migration;

class m170216_164132_change_eav_value_column_type extends Migration
{
    public function up()
    {
        $this->alterColumn('eav_value', 'value', 'LONGBLOB NULL');
    }

}
