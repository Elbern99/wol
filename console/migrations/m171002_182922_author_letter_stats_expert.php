<?php

use yii\db\Migration;


class m171002_182922_author_letter_stats_expert extends Migration
{


    private $letterView = '{{%author_letter_stats_expert}}';

    private $viewQuery = 'SELECT al.id, al.letter as {{%author_letter}}, (SELECT count(a.id) FROM {{%author}} a INNER JOIN {{%author_roles}} `ar` ON a.id = ar.author_id AND ar.role_id=2 WHERE a.enabled=1 AND a.surname LIKE CONCAT(al.letter, "%")) as author_count FROM `author_letter` al group by al.id';


    public function safeUp()
    {
        $this->execute('CREATE VIEW ' . $this->letterView . ' AS (' . $this->viewQuery . ')');
    }


    public function safeDown()
    {
        $this->execute('DROP VIEW ' . $this->letterView);
    }
}
