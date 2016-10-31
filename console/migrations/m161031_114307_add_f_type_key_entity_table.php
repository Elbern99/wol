<?php

use yii\db\Migration;

class m161031_114307_add_f_type_key_entity_table extends Migration
{
    public function up()
    {
        $this->addForeignKey('FK_Entity_TypeId', 'eav_entity', 'type_id', 'eav_type', 'id');
    }

    public function down()
    {
         $this->dropForeignKey('FK_Entity_TypeId', 'eav_entity');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
