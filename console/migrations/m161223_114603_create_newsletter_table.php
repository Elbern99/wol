<?php

use yii\db\Migration;

/**
 * Handles the creation of table `newsletter`.
 */
class m161223_114603_create_newsletter_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('newsletter', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'areas_interest' => $this->string()->null(),
            'interest' => $this->boolean()->defaultValue(0),
            'iza_world' => $this->boolean()->defaultValue(0),
            'iza' => $this->boolean()->defaultValue(0),
            'created_at'=> $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('newsletter');
    }
}
