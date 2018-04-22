<?php

use yii\db\Migration;
use common\models\ArticleCreated;


class m171210_231353_fill_doi_control extends Migration
{


    public function safeUp()
    {
        foreach (ArticleCreated::find()->joinWith(['article'])->each() as $a) {
            if ($a->article) {
                $a->doi_control = $a->article->doi;
                $a->save();
            }
            
            echo $a->article_id . ' ';
        }

        echo "\n> Finished.\n";
    }


    public function safeDown()
    {
        echo "No actions needed to revert m171210_231353_fill_doi_control.\n";
        return true;
    }
}
