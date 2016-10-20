<?php

use yii\db\Migration;

/**
 * Handles the creation for table `widget`.
 */
class m161019_135916_create_widget_table extends Migration {

    const TABLE_NAME = '{{%widget}}';

    /**
     * @inheritdoc
     */
    public function up() {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(60)->notNull()->unique(),
            'text' => $this->text()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable(self::TABLE_NAME);
    }

}
