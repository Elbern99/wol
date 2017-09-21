<?php

use yii\db\Migration;
use common\models\Article;
use common\models\eav\EavEntity;


class m170921_223858_kill_wrong_article_379 extends Migration
{


    const ERROR_UPLOAD = 379;


    public function safeUp()
    {
        Article::deleteAll(['id' => self::ERROR_UPLOAD]);
        EavEntity::deleteAll(['model_id' => self::ERROR_UPLOAD]);
    }


    public function safeDown()
    {
        echo "m170921_223858_kill_wrong_article_379 cannot be reverted.\n";
        return false;
    }
}
