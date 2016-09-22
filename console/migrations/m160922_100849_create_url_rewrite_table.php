<?php

use yii\db\Migration;

/**
 * Handles the creation for table `url_rewrite`.
 */
class m160922_100849_create_url_rewrite_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('url_rewrite', [
            'id' => $this->primaryKey(),
            'current_path' => $this->string()->notNull(),
            'rewrite_path' => $this->string()->notNull(),
        ]);
        
        $this->createIndex('url_rewrite-path-index', 'url_rewrite', 'current_path', true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('url_rewrite-path-index', 'url_rewrite');
        $this->dropTable('url_rewrite');
    }
}
