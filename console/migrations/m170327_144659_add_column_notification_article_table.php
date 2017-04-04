<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notification_version`.
 */
class m170327_144659_add_column_notification_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('article', 'notices', 'BLOB NULL');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('article', 'notices');
    }
}
