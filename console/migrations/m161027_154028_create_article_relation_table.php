<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_relation`.
 */
class m161027_154028_create_article_relation_table extends Migration
{
    const TABLE_NAME = '{{%article_relation}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'article_relation_id' => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('FK_ArticleRelation_ArticleId', self::TABLE_NAME, 'article_id', 'article', 'id', 'CASCADE');
        $this->addForeignKey('FK_ArticleRelation_ArticleRelationId', self::TABLE_NAME, 'article_relation_id', 'article', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
