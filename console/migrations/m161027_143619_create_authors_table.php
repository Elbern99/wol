<?php

use yii\db\Migration;

/**
 * Handles the creation of table `authors`.
 */
class m161027_143619_create_authors_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('author', [
            'id' => $this->primaryKey(),
            'author_key' => $this->string(50)->notNull()->unique(),
            'email' => $this->string(),
            'phone' => $this->string(50),
            'enable' => $this->boolean()->defaultValue(1)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('author');
    }
}
