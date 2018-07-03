<?php

use yii\db\Migration;


/**
 * Class m180703_002556_add_version_field
 */
class m180703_002556_add_version_field extends Migration
{


    private $a = '{{%article}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('article-seo-unique', $this->a);
        $this->addColumn($this->a, 'version', $this->integer()->unsigned()->notNull()->defaultValue(1));
        $this->createIndex('uniq_seo_version', $this->a, ['seo', 'version'], true);
        $this->createIndex('uniq_doi_version', $this->a, ['doi', 'version'], true);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180703_003301_add_version_field cannot be reverted.\n";

        return false;
        /*
         * Use only if you remove all old versions of article
         * 
          $this->dropIndex('uniq_seo_version', $this->a);
          $this->dropIndex('uniq_doi_version', $this->a);
          $this->dropColumn($this->a, 'version');
          $this->createIndex('article-seo-unique', $this->a, 'seo', true);
         * 
         */
    }
}
