<?php

use yii\db\Migration;


/**
 * Class m180921_223306_extend_newsletter_text
 */
class m180921_223306_extend_newsletter_text extends Migration
{


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('newsletter_news', 'main', 'MEDIUMTEXT NOT NULL');
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180921_223306_extend_newsletter_text cannot be reverted.\n";

        return false;
    }
}
