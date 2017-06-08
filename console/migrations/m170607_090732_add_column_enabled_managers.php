<?php

use yii\db\Migration;

class m170607_090732_add_column_enabled_managers extends Migration
{
    public function safeUp()
    {
        $this->addColumn('news', 'enabled', $this->boolean()->defaultValue(1));
        $this->addColumn('events', 'enabled', $this->boolean()->defaultValue(1));
        $this->addColumn('opinions', 'enabled', $this->boolean()->defaultValue(1));
        $this->addColumn('press_releases', 'enabled', $this->boolean()->defaultValue(1));
        $this->addColumn('topics', 'enabled', $this->boolean()->defaultValue(1));
    }

    public function safeDown()
    {
        $this->dropColumn('news', 'enabled');
        $this->dropColumn('events', 'enabled');
        $this->dropColumn('opinions', 'enabled');
        $this->dropColumn('press_releases', 'enabled');
        $this->dropColumn('topics', 'enabled');
    }

}
