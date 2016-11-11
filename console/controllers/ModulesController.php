<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use common\models\Modules;
use Yii;

class ModulesController extends Controller {
    
    /* php yii modules/init */
    public function actionInitAuthor() {
        
        try {
            
            $bulkInsertArray = [
                ['key'=>'accordion', 'title'=>'Accordion', 'system'=>1],
                ['key'=>'simple', 'title'=>'Simple Page', 'system'=>1]
            ];
            
            $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                        Modules::tableName(), ['key', 'title', 'system'], 
                        $bulkInsertArray
                    )
                    ->execute();
            
            if ($insertCount) {
                $this->stdout("Modules init success", Console::BG_GREEN);
                echo "\n";
                return 1;
            }
            
        } catch (\yii\db\Exception $e) {
            $this->stdout("Modules init failed", Console::BG_RED);
            echo "\n";
            return 0;
        } catch (\Exception $e) {
            $this->stdout("Modules init failed", Console::BG_RED);
            echo "\n";
            return 0;
        }

        return 0;
    }
}