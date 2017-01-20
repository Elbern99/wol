<?php

use yii\db\Migration;

class m170119_172721_add_activate_column_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'activated', $this->boolean()->defaultValue(0));
    }
}
