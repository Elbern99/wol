<?php

use yii\db\Migration;

/**
 * Handles the creation of table `data_source_taxonomy`.
 */
class m170116_145253_create_data_source_taxonomy_table extends Migration
{
    const TABLE_NAME = 'source_taxonomy';
    
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'source_id' => $this->integer(),
            'taxonomy_id' => $this->integer()
        ]);
        
        $this->addForeignKey('FK_SourceTaxonomy_SourceId', self::TABLE_NAME, 'source_id', 'data_source', 'id', 'CASCADE');
        $this->addForeignKey('FK_SourceTaxonomy_TaxonomyId', self::TABLE_NAME, 'taxonomy_id', 'taxonomy', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
