<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use common\models\SearchResult;
use Yii;

class SearchResultController extends Controller {
    
    /* php yii search-result/check */
    public function actionCheck() {
        
        //try {
            
            $model = new SearchResult();
            $model->removeExpiresSearchResult();
            
            $this->stdout("Modules init success", Console::BG_GREEN);
            echo "\n";
            return 1;
            
            
        /*} catch (\yii\db\Exception $e) {
            $this->stdout("Modules init failed", Console::BG_RED);
            echo "\n";
            return 0;
        } catch (\Exception $e) {
            $this->stdout("Modules init failed", Console::BG_RED);
            echo "\n";
            return 0;
        }*/

        return 0;
    }
}