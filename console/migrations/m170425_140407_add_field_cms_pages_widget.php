<?php

use yii\db\Migration;

class m170425_140407_add_field_cms_pages_widget extends Migration
{
    public function up()
    {
        $this->addColumn('cms_pages_widget', 'order', $this->smallInteger()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('cms_pages_widget', 'order');
    }
}
