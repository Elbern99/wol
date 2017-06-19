<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\Newsletter;
use common\models\User;
use console\models\Category;
use yii\helpers\ArrayHelper;

/*
 * Command example add admin user php yii subscribers-export
 */
class SubscribersExportController extends Controller
{

    public function actionIndex()
    {
        $parent = Category::find()
                             ->where(['url_key' => 'articles'])
                             ->select(['root', 'lvl', 'lft', 'rgt'])
                             ->one();
        

        $categories =  $parent->children()
                            ->select([
                               'id', 'taxonomy_code'
                            ])
                            ->asArray()
                            ->all();
        
        $categories = ArrayHelper::map($categories, 'id', 'taxonomy_code');
        
        $subscribers = Newsletter::find()->alias('n')
                                         ->select([
                                            'email', 'first_name', 'last_name', 
                                            'areas_interest', 'interest', 'iza_world', 
                                            'iza'
                                         ])
                                         ->asArray()
                                         ->all();
        
        
        if (count($subscribers)) {
            $filePath = Yii::$app->basePath.'/runtime/logs/file.csv';
            
            $fp = fopen($filePath, 'w+');
            fputcsv($fp, ['email', 'first_name', 'last_name', 
                                            'areas_interest', 'interest', 'iza_world', 
                                            'iza', 'registered']);
            foreach ($subscribers as $fields) {
                $areas_interest = explode(',', $fields['areas_interest']);
                $areas_interest_taxonomy = [];
                
                foreach ($areas_interest as $ai) {
                    if (isset($categories[$ai])) {
                        $areas_interest_taxonomy[] = $categories[$ai];
                    }

                }
                
                $fields['areas_interest'] = implode(',', $areas_interest_taxonomy);
                $user = User::find()->select('id')->where(['email' => $fields['email']])->one();
                $fields['registered'] = 0;
                
                if (is_object($user) && $user->id) {
                    $fields['registered'] = 1;
                }

                fputcsv($fp, $fields);
            }

            fclose($fp);
        }

        $this->stdout("Attribute password/username/email not exists.", Console::BG_RED);
        echo "\n";
        return 1;

        /*$this->stdout("User can not added.", Console::BG_RED);
        echo "\n";

        return 0;*/
    }
}
