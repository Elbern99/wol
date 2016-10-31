<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use common\modules\article\ArticleSchemaAttributes;
use Yii;

class ArticleController extends Controller {
    
    /* php yii article/init-attribute */
    public function actionInitAttribute() {
        
        $storage = Yii::$container->get('eav_storage');
        
        try {

            $typeModel = $storage->factory('type');
            $typeModel->addType('article');

            if (!$typeModel->id) {
                throw new \Exception();
            }

            $attributes = Yii::createObject(ArticleSchemaAttributes::class);
            $attributes = $attributes->getAttributes();
            $ids = [];

            foreach ($attributes as $attribute) {
                $attributeModel = $storage->factory('attribute');
                $attributeModel->addAttributeWithOptions($attribute);

                if ($attributeModel->id) {
                    $ids[] = $attributeModel->id;
                }
            }

            $attributeTypeModel = $storage->factory('atribute_type');
            $attributeTypeModel->addAttributeType($typeModel->id, $ids);
            
            
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