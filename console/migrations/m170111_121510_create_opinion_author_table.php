<?php

use yii\db\Migration;

/**
 * Handles the creation of table `opinion_author`.
 */
class m170111_121510_create_opinion_author_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('opinion_authors', [
            'id' => $this->primaryKey(),
            'opinion_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);
        
        
        $this->addForeignKey('fk-opinion_authors_opinion_id', 'opinion_authors', 'opinion_id', 'opinions', 'id', 'CASCADE');
        $this->addForeignKey('fk-opinion_authors_author_id', 'opinion_authors', 'author_id', 'author', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('opinion_authors');
    }
}
