<?php

use yii\db\Migration;


/**
 * Class m180801_233200_version_difference
 */
class m180801_233200_version_difference extends Migration
{


    private $a = '{{%article}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->a, 'max_version', $this->integer()->unsigned()->notNull()->defaultValue(1));

        $cmd = $this->db->createCommand('select * from ' . $this->a);

        foreach ($cmd->query() as $row) {
            $maxVersion = $this->db->createCommand('select max(version) as maxVersion from ' . $this->a . ' where doi=:doi', ['doi' => $row['doi']])->queryScalar();
            $this->db->createCommand()->update($this->a, ['max_version' => $maxVersion], 'id='.$row['id'])->execute();
        }
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->a, 'max_version');
    }
}
