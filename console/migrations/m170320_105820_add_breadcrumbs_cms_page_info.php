<?php

use yii\db\Migration;

class m170320_105820_add_breadcrumbs_cms_page_info extends Migration
{
    public function up()
    {
        $this->addColumn('cms_page_info', 'breadcrumbs', 'TINYBLOB NULL');
    }

    public function down()
    {
        $this->dropColumn('cms_page_info', 'breadcrumbs');
    }

}
