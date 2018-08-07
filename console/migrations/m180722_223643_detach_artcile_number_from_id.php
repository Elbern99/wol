<?php

use yii\db\Migration;


/**
 * Class m180722_223643_detach_artcile_number_from_id
 */
class m180722_223643_detach_artcile_number_from_id extends Migration
{


    private $a = '{{%article}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->a, 'article_number', $this->integer()->null()->defaultValue(null));
        $this->execute('update ' . $this->a . ' SET article_number=id');
        $this->alterColumn($this->a, 'article_number', $this->integer()->notNull());
        $this->createIndex('uniq_article_number_version', $this->a, ['article_number', 'version'], true);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->a, 'article_number');
    }
}
