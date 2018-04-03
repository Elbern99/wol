<?php

use yii\db\Migration;
use common\models\Newsletter;


class m180403_231422_check_wrong_emails_in_newsletter extends Migration
{


    public function safeUp()
    {
        echo "START Email checking in newsletter\r\n";
        $ids = [];

        foreach (Newsletter::find()->each() as $model) {
            if (!$model->validate(['email'])) {
                echo ' >' . $model->email . "\r\n";
                $ids[] = $model->id;
            }
        }

        echo "FOUND errors: " . count($ids) . ", deleting...\r\n";
        Newsletter::deleteAll(['id' => $ids]);
        echo "DONE.\r\n";
    }


    public function safeDown()
    {
        echo "No action needed to revert m180403_231422_check_wrong_emails_in_newsletter.\n";
        return true;
    }
}
