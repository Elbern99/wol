<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `key_words_article`.
 */
class m161101_164440_drop_keywords_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('article', 'keywords');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('article', 'keywords', $this->string(50));
    }
}
