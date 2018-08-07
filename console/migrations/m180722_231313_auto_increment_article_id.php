<?php

use yii\db\Migration;


/**
 * Class m180722_231313_auto_increment_article_id
 */
class m180722_231313_auto_increment_article_id extends Migration
{


    private $a = '{{%article}}';

    private $aa = '{{%article_author}}';

    private $ac = '{{%article_category}}';

    private $ar = '{{%article_relation}}';

    private $af = '{{%favorit_article}}';

    private $ta = '{{%topic_articles}}';

    private $na = '{{%news_articles}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('FK_ArticleAuthor_ArticleId', $this->aa);
        $this->dropForeignKey('FK_ArticleCategroy_ArticleId', $this->ac);
        $this->dropForeignKey('FK_ArticleFavorit_ArticleId', $this->af);
        $this->dropForeignKey('fk-news_articles_article_id', $this->na);
        $this->dropIndex('id', $this->a);
        $this->execute('ALTER TABLE ' . $this->a . ' MODIFY id INT PRIMARY KEY AUTO_INCREMENT;');
        $this->addForeignKey('FK_ArticleAuthor_ArticleId', $this->aa, 'article_id', $this->a, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_ArticleCategroy_ArticleId', $this->ac, 'article_id', $this->a, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_ArticleFavorit_ArticleId', $this->af, 'article_id', $this->a, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-news_articles_article_id', $this->na, 'article_id', $this->a, 'id', 'CASCADE', 'CASCADE');
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180722_231313_auto_increment_article_id cannot be reverted.\n";

        return false;
    }
}
