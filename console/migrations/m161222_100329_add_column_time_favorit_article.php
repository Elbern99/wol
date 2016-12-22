<?php

use yii\db\Migration;

class m161222_100329_add_column_time_favorit_article extends Migration
{
    public function up()
    {
        $this->addColumn('favorit_article', 'created_at', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('favorit_article', 'created_at');
    }

}
