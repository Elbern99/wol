<?php

use yii\db\Migration;

/**
 * Handles the creation of table `video_relations`.
 */
class m170104_101109_create_video_relations_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('video_relations', [
            'parent_id' => $this->integer()->notNull(),
            'children_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-video_relations_parent_id', 'video_relations', 'parent_id', 'video', 'id', 'CASCADE');
        $this->addForeignKey('fk-video_relations_children_id', 'video_relations', 'children_id', 'video', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('video_relations');
    }
}
