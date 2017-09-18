<?php

use yii\db\Migration;
use common\models\Category;


class m170918_193320_staging_cleanup extends Migration
{


    private $aliases = [
        '/subject-areas/labor-market-in-germany',
        '/subject-areas/country-labor-markets',
    ];


    public function safeUp()
    {/*
     * COMMENT IT TO PREVENT USING - IT WAS RUN-ONCE fix
        for ($i = 0; $i < count($this->aliases); $i++) {
            $urlkey = $this->aliases[$i];
            $cmd = Yii::$app->db->createCommand('SELECT * FROM category WHERE url_key="' . $urlkey . '"');

            $result = $cmd->queryOne();

            if ($result) {
                print_r($result);
                $left = $result['lft'];
                $right = $result['rgt'];
                Yii::$app->db->createCommand('DELETE FROM category WHERE lft >= ' . $left . ' AND rgt <= ' . $right)->execute();
                Yii::$app->db->createCommand('UPDATE category SET lft = IF(lft > ' . $left . ', lft - (' . $right . ' - ' . $left . ' + 1), lft), rgt = rgt - (' . $right . ' - ' . $left . ' + 1) WHERE rgt > ' . $right)->execute();
            }
        }
     * 
     */
    }


    public function safeDown()
    {
        echo "No action needed to step down.\n";

        return true;
    }
}
