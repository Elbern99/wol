<?php

use yii\db\Migration;

/**
 * Handles the creation of table `homepage_commentary`.
 */
class m170116_123629_create_homepage_commentary_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('homepage_commentary', [
            'id' => $this->primaryKey(),
            'object_id' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('homepage_commentary');
    }
}
