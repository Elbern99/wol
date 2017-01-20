<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_activation`.
 */
class m170119_170532_create_user_activation_table extends Migration
{
    const TABLE_NAME = 'user_activation';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);
        
        $this->createIndex('Index_UserActivation_Token', self::TABLE_NAME, 'token');
        $this->addForeignKey('FK_UserActivation_UserId', self::TABLE_NAME, 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
