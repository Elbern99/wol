<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log`.
 */
class m200901_122320_add_column_sort_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('category', 'sort_index', $this->integer()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('category', 'sort_index');
    }
}
