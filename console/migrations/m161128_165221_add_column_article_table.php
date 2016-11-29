<?php

use yii\db\Migration;

class m161128_165221_add_column_article_table extends Migration
{
    public function up()
    {
        $this->addColumn('article', 'title', $this->string()->notNull());
        $this->createIndex('article-seo-unique', 'article', 'seo', true);
    }

    public function down()
    {
        $this->dropColumn('article', 'title');
    }

}
