<?php

use yii\db\Migration;


/**
 * Class m180710_215115_increase_search_filters
 */
class m180710_215115_increase_search_filters extends Migration
{


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('search_result', 'filters', 'MEDIUMBLOB NULL DEFAULT NULL');
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180710_215115_increase_search_filters cannot be reverted.\n";

        return false;
    }
}
