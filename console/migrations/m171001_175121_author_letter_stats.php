<?php

use yii\db\Migration;

class m171001_175121_author_letter_stats extends Migration
{
    private $letterView = '{{%author_letter_stats}}';
    
    private $viewQuery = 'SELECT al.id, al.letter as {{%author_letter}}, (SELECT count(a.id) FROM {{%author}} a INNER JOIN {{%author_roles}} `ar` ON a.id = ar.author_id AND ar.role_id=1 WHERE a.enabled=1 AND a.surname LIKE CONCAT(al.letter, "%")) as author_count FROM `author_letter` al group by al.id';
    
    public function safeUp()
    {
        $this->execute('CREATE VIEW '.$this->letterView.' AS ('.$this->viewQuery.')');
    }

    public function safeDown()
    {
        $this->execute('DROP VIEW '.$this->letterView);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171001_175121_author_letter_stats cannot be reverted.\n";

        return false;
    }
    */
}
