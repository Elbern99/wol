<?php

use yii\db\Migration;

/**
 * Handles the creation for table `category`.
 */
class m161005_124238_create_category_table extends Migration
{
     const TABLE_NAME = '{{%category}}';

    public function up() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->bigPrimaryKey(),
            'url_key' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->null(),
            'meta_title' => $this->string()->notNull(),
            'meta_keywords' => $this->text()->null(),
            'root' => $this->integer(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'lvl' => $this->smallInteger(5)->notNull(),
            'type' => $this->smallInteger(5)->notNull(),
            'active' => $this->boolean()->defaultValue(false),
            'filterable' => $this->boolean()->defaultValue(false),
            'visible_in_menu' => $this->boolean()->defaultValue(false),
        ], $tableOptions);

        $this->createIndex('tree_NK1', self::TABLE_NAME, 'root');
        $this->createIndex('tree_NK2', self::TABLE_NAME, 'lft');
        $this->createIndex('tree_NK3', self::TABLE_NAME, 'rgt');
        $this->createIndex('tree_NK4', self::TABLE_NAME, 'lvl');
        $this->createIndex('tree_NK5', self::TABLE_NAME, 'active');
        $this->createIndex('tree_NK6', self::TABLE_NAME, 'type');
        $this->createIndex('tree_url_key_unique', self::TABLE_NAME, 'url_key', true);
    }

    public function down() {
        $this->dropTable(self::TABLE_NAME);
    }

}
