<?php

use yii\db\Migration;

/**
 * Handles the creation of table `saved_search`.
 */
class m161222_145942_create_saved_search_table extends Migration
{
    const TABLE_NAME = 'saved_search';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'search_phrase' => $this->string()->notNull(),
            'types' => $this->string(50)->notNull(),
            'all_words' => $this->string()->null(),
            'any_words' => $this->string()->null(),
            'created_at'=> $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('FK_SavedSearch_UserId', self::TABLE_NAME, 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
