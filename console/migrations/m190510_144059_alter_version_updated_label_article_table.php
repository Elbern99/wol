<?php

use yii\db\Migration;

/**
 * Class m190510_144059_alter_version_updated_label_article_table
 */
class m190510_144059_alter_version_updated_label_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%article}}', 'version_updated_label', $this->boolean()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%article}}', 'version_updated_label');
    }
}
