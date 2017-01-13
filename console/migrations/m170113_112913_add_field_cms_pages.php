<?php

use yii\db\Migration;

class m170113_112913_add_field_cms_pages extends Migration
{
    public function up()
    {
        $this->addColumn('cms_pages', 'system', $this->boolean()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('cms_pages', 'system');
    }
}
