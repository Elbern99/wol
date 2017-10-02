<?php

use yii\db\Migration;


class m171002_183544_author_letter_stats_editor extends Migration
{


    private $letterView = '{{%author_letter_stats_editor}}';

    private $viewQuery = 'SELECT al.id, al.letter as {{%author_letter}}, (SELECT count(a.id) FROM {{%author}} a INNER JOIN {{%author_roles}} `ar` ON a.id = ar.author_id AND ar.role_id IN (3,4,5,6,7) WHERE a.enabled=1 AND a.surname LIKE CONCAT(al.letter, "%")) as author_count FROM `author_letter` al group by al.id';


    public function safeUp()
    {
        $this->execute('CREATE VIEW ' . $this->letterView . ' AS (' . $this->viewQuery . ')');
    }


    public function safeDown()
    {
        $this->execute('DROP VIEW ' . $this->letterView);
    }
}
