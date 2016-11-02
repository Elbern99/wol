<?php

use yii\db\Migration;

class m161102_103234_update_article_author_table extends Migration {

    public function up() {
        
        $this->addColumn('author', 'avatar', $this->string(50));
        $this->addColumn('article', 'publisher', $this->string(50));
        
    }

    public function down() {
        
        $this->dropColumn('author', 'avatar');
        $this->dropColumn('article', 'publisher');
        
    }

}
