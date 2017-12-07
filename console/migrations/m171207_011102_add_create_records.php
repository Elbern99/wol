<?php

use yii\db\Migration;
use common\models\Article;


class m171207_011102_add_create_records extends Migration
{


    public function safeUp()
    {
        foreach (Article::find()->each() as $a) {
            $a->insertOrUpdateCreateRecord();
            echo $a->id . ' ';
        }

        echo "\n> Finished.\n";
    }


    public function safeDown()
    {
        echo "No actions needed to revert m171207_011102_add_create_records.\n";
        return true;
    }
}
