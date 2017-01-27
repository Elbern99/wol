<?php

use yii\db\Migration;

class m170126_084736_add_column_source_taxonomy_table extends Migration {

    const TABLE_NAME = 'source_taxonomy';

    public function up() {
        $this->addColumn(self::TABLE_NAME, 'additional_taxonomy_id', $this->integer());
        $this->addForeignKey('FK_AdditionalTaxonomy_TaxonomyId', self::TABLE_NAME, 'taxonomy_id', 'taxonomy', 'id', 'CASCADE');
    }

    public function down() {
        $this->dropForeignKey('FK_AdditionalTaxonomy_TaxonomyId', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'additional_taxonomy_id');
    }

}
