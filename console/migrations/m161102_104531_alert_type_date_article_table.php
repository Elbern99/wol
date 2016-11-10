<?php

use yii\db\Migration;

class m161102_104531_alert_type_date_article_table extends Migration
{
    public function up()
    {
        $this->alterColumn('article', 'updated_at', $this->integer()->null());
        $this->alterColumn('article', 'created_at', $this->integer()->null());
        $this->alterColumn('article', 'id', $this->integer()->notNull());
    }

    public function down()
    {
        $this->alterColumn('article', 'created_at', $this->integer()->notNull());
        $this->alterColumn('article', 'updated_at', $this->integer()->notNull());
        
        $this->alterColumn('article', 'id', $this->integer()->null());
    }

}
