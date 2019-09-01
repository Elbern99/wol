<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log`.
 */
class m190901_122320_create_newsletter_logs_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%newsletter_logs}}', [
            'id' => $this->primaryKey(),
            'subject' => $this->string(255)->notNull(),
            'qty' => $this->integer()->notNull(),
            'error_text' => $this->text()->null(),
            'status' => $this->integer()->notNull(),
            'progress' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createTable('{{%newsletter_logs_users}}', [
            'newsletter_id' => $this->integer()->notNull(),
            'newsletter_logs_id' => $this->integer()->notNull()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('INDEX-NewsLetterId', '{{%newsletter_logs_users}}', 'newsletter_id');
        $this->createIndex('INDEX-NewsLetterLogsId', '{{%newsletter_logs_users}}', 'newsletter_logs_id');

        $this->addForeignKey(
            'FK_NewsletterLogsUsers_NewsLetterId',
            '{{%newsletter_logs_users}}',
            'newsletter_id',
            '{{%newsletter}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'FK_NewsletterLogsUsers_NewsLetterLogsId',
            '{{%newsletter_logs_users}}',
            'newsletter_logs_id',
            '{{%newsletter_logs}}',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%newsletter_logs_article}}', [
            'article_id' => $this->integer()->notNull(),
            'newsletter_logs_id' => $this->integer()->notNull()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('INDEX-ArticleId', '{{%newsletter_logs_article}}', 'article_id');
        $this->createIndex('INDEX-NewsLetterLogsId', '{{%newsletter_logs_article}}', 'newsletter_logs_id');

        $this->addForeignKey(
            'FK_NewsletterLogsArticle_ArticleId',
            '{{%newsletter_logs_article}}',
            'article_id',
            '{{%article}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'FK_NewsletterLogsArticle_NewsLetterLogsId',
            '{{%newsletter_logs_article}}',
            'newsletter_logs_id',
            'newsletter_logs',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('FK_NewsletterLogsArticle_NewsLetterLogsId', '{{%newsletter_logs_article}}');
        $this->dropForeignKey('FK_NewsletterLogsArticle_ArticleId', '{{%newsletter_logs_article}}');
        $this->dropIndex('INDEX-NewsLetterLogsId', '{{%newsletter_logs_article}}');
        $this->dropIndex('INDEX-ArticleId', '{{%newsletter_logs_article}}');
        $this->dropTable('{{%newsletter_logs_article}}');

        $this->dropForeignKey('FK_NewsletterLogsUsers_NewsLetterLogsId', '{{%newsletter_logs_users}}');
        $this->dropForeignKey('FK_NewsletterLogsUsers_NewsLetterId', '{{%newsletter_logs_users}}');
        $this->dropIndex('INDEX-NewsLetterLogsId', '{{%newsletter_logs_users}}');
        $this->dropIndex('INDEX-NewsLetterId', '{{%newsletter_logs_users}}');
        $this->dropTable('{{%newsletter_logs_users}}');

        $this->dropTable('{{%newsletter_logs}}');
    }
}
