<?php

use yii\db\Migration;

/**
 * Handles the creation of table `lang`.
 */
class m161110_092541_create_lang_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('lang', [
            'id' => $this->primaryKey(),
            'code' => $this->string(5)->notNull()->unique(),
            'name' => $this->string(15)->notNull(),
            'image' => $this->string(50)->notNull()
        ]);
        
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('lang');
    }
}
