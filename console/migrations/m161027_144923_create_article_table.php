<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m161027_144923_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->integer()->unique(),
            'sort_key' => $this->string()->notNull(),
            'seo' => $this->string()->notNull(),
            'doi' => $this->string(50)->notNull(),
            'availability' => $this->string(50),
            'keywords' => $this->string(50),
            'enabled' => $this->boolean()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
