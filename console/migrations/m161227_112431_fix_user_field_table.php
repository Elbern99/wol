<?php
use yii\db\Migration;

class m161227_112431_fix_user_field_table extends Migration {

    public function up() {
        
        $this->dropIndex('username', 'user');
    }
}
