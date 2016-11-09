<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `article_relation`.
 */
class m161109_084350_drop_article_relation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('article_relation');
    }

}
