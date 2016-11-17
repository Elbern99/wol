<?php

use yii\db\Migration;

/**
 * Handles the creation of table `author_roles`.
 */
class m161117_151422_create_author_roles_table extends Migration
{
    
    const TABLE_NAME = 'author_roles';
    
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('FK_AuthorRoles_AuthorId', self::TABLE_NAME, 'author_id', 'author', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
