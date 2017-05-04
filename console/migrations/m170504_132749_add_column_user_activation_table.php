<?php

use yii\db\Migration;

class m170504_132749_add_column_user_activation_table extends Migration
{
    public function up()
    {
        $this->addColumn('user_activation', 'new_email', $this->string()->null());
    }

    public function down()
    {
        $this->dropColumn('user_activation', 'new_email');
    }
}
