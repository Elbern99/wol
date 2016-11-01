<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use common\modules\author\AuthorSchemaAttributes;
use Yii;

class AuthorController extends Controller {
    
    /* php yii author/init-author */
    public function actionInitAuthor() {
        
        $storage = Yii::$container->get('eav_storage');
        
        try {

            $typeModel = $storage->factory('type');
            $typeModel->addType('author');

            if (!$typeModel->id) {
                throw new \Exception();
            }

            $attributes = Yii::createObject(AuthorSchemaAttributes::class);
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
            $this->stdout("Author init failed", Console::BG_RED);
            echo "\n";
            return 0;
        } catch (\Exception $e) {
            $this->stdout("Author init failed", Console::BG_RED);
            echo "\n";
            return 0;
        }

        $this->stdout("Author init success", Console::BG_GREEN);
        echo "\n";
        return 1;
    }
}