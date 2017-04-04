<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;
use common\modules\article\ArticleSchemaAttributes;
use common\models\VersionsArticle;
use Yii;

class VersionsController extends Controller {
    
    /* php yii versions/init-attribute */
    public function actionInitAttribute() {
        
        $storage = Yii::$container->get('eav_storage');
        
        try {

            $typeModel = $storage->factory('type');
            $typeModel->addType(VersionsArticle::ENTITY_NAME);

            if (!$typeModel->id) {
                throw new \Exception();
            }

            $attributes = Yii::createObject(ArticleSchemaAttributes::class);
            $attributes = $attributes->getAttributes();
            $attributes = array_map(function($attribute) {
                return $attribute->getName();
            }, $attributes);
            
            $attributeModel = $storage->factory('attribute');
            $attributes = $attributeModel->find()->select('id')->where(['name' => $attributes])->asArray()->all();
            
            if (!is_array($attributes)) {
                throw new \Exception('Attributes not created');
            }
            
            $ids = ArrayHelper::getColumn($attributes, 'id');

            $attributeTypeModel = $storage->factory('atribute_type');
            $attributeTypeModel->addAttributeType($typeModel->id, $ids);
            
            
        } catch (\yii\db\Exception $e) {
            $this->stdout("Versioning init failed", Console::BG_RED);
            echo "\n";
            return 0;
        } catch (\Exception $e) {
            $this->stdout("Versioning init failed", Console::BG_RED);
            echo "\n";
            return 0;
        }

        $this->stdout("Versioning init success", Console::BG_GREEN);
        echo "\n";
        return 1;
    }
}