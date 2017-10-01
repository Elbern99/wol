<?php

use yii\db\Migration;


class m171001_173948_letters extends Migration
{


    private $letters = '{{%author_letter}}';


    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->letters, [
            'id' => $this->primaryKey(),
            'letter' => $this->string(1)->notNull(),
            ], $tableOptions);
        
        $alphas = range('A', 'Z');
        
        foreach ($alphas as $key => $letter) {
            $this->insert($this->letters, ['letter' => $letter]);
        }
    }


    public function safeDown()
    {
        $this->dropTable($this->letters);
    }
    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m171001_173948_letters cannot be reverted.\n";

      return false;
      }
     */
}
