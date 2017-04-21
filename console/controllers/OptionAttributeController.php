<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use common\modules\article\ArticleSchemaAttributes;
use common\modules\author\AuthorSchemaAttributes;
use common\models\eav\EavAttribute;
use common\models\eav\EavAttributeOption;
use Yii;

class OptionAttributeController extends Controller {
    
    /* php yii option-attribute/add-attribute-options */
    public function actionAddAttributeOptions($type) {

        try {
            
            if ($type == 'author') {
                $attributes = Yii::createObject(AuthorSchemaAttributes::class);
            } else {
                $attributes = Yii::createObject(ArticleSchemaAttributes::class);
            }
            
            $attributes = $attributes->getAttributes();

            foreach ($attributes as $attribute) {
                
                $model = EavAttribute::find()->where(['name' => $attribute->getName()])->one();
                
                if (isset($model->id)) {
                    $options = [];

                    foreach ($attribute->getOptions() as $option) {
                        $option['attribute_id'] = $model->id;
                        $options[] = $option;
                    }

                    $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                       EavAttributeOption::tableName(), ['label', 'type', 'attribute_id'], $options
                    )
                    ->execute();
                }
            }

        } catch (\yii\db\Exception $e) {
            $this->stdout("Article init failed", Console::BG_RED);
            echo "\n";
            return 0;
        } catch (\Exception $e) {
            $this->stdout("Article init failed", Console::BG_RED);
            echo "\n";
            return 0;
        }

        $this->stdout("Article init success", Console::BG_GREEN);
        echo "\n";
        return 1;
    }
}