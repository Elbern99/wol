<?php

use yii\db\Migration;

/**
 * Handles the creation of table `author_category`.
 */
class m161117_151315_create_author_category_table extends Migration
{
    
    const TABLE_NAME = 'author_category';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'category_id' => $this->bigInteger(20)->notNull()
        ]);
        
        $this->addForeignKey('FK_AuthorCategory_AuthorId', self::TABLE_NAME, 'author_id', 'author', 'id', 'CASCADE');
        $this->addForeignKey('FK_AuthorCategory_CategoryId', self::TABLE_NAME, 'category_id', 'category', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
