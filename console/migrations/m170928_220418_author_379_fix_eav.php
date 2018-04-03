<?php

use yii\db\Migration;
use common\models\Author;
use common\models\eav\EavEntity;
use common\models\eav\EavType;


class m170928_220418_author_379_fix_eav extends Migration
{


    const LIVE_AUTHOR_ID = 379;


    public function safeUp()
    {
        $author = Author::findOne(['id' => self::LIVE_AUTHOR_ID]);

        if (!$author || ($author->name != 'Ulf  Rinne')) {
            return true;
        }

        $type = EavType::findOne(['name' => 'author']);

        if (!$type) {
            return true;
        }

        $entity = EavEntity::find()
            ->where([
                'type_id' => $type->id,
                'model_id' => $author->id
            ])
            ->one();
        
        if (!$entity) {
            $entity = new EavEntity();
            $entity->setAttributes(['model_id' => $author->id, 'type_id' => $type->id, 'name' => $type->name . '_' . $author->id], false);
            $entity->save();
//            $this->insert(EavEntity::tableName(), ['model_id' => $author->id, 'type_id' => $type->id, 'name' => $type->name . '_' . $author->id]);
            $query = str_replace('{:id}', $entity->id, $this->values);
            $this->execute($query);
        }
    }


    public function safeDown()
    {
        echo "m170928_220418_author_379_fix_eav cannot be reverted.\n";

        return false;
    }

    private $values = " 
INSERT INTO `eav_value` (`entity_id`, `attribute_id`, `lang_id`, `value`) VALUES
({:id}, 34, 0, 0x4f3a383a22737464436c617373223a343a7b733a393a22686f6e6f7269666963223b733a303a22223b733a31303a2266697273745f6e616d65223b733a333a22556c66223b733a31313a226d6964646c655f6e616d65223b733a303a22223b733a393a226c6173745f6e616d65223b733a353a2252696e6e65223b7d),
({:id}, 35, 0, 0x613a313a7b693a303b4f3a383a22737464436c617373223a313a7b733a31313a2274657374696d6f6e69616c223b733a3230313a2254686520495a4120576f726c64206f66204c61626f7220636f756c642062652074686520756c74696d617465207265736f7572636520666f7220696e666f726d6564206465626174657320616e640a20202020202020202020202065766964656e63652d626173656420706f6c696379206465636973696f6e732c206576656e7475616c6c7920726573756c74696e6720696e20626574746572206f7574636f6d65732e204974206973206120676966740a202020202020202020202020666f7220736f6369657479223b7d7d),
({:id}, 36, 0, 0x613a323a7b693a303b4f3a383a22737464436c617373223a313a7b733a31313a227075626c69636174696f6e223b733a3136323a22e2809c416e6f746865722065636f6e6f6d6963206d697261636c653f20546865204765726d616e206c61626f72206d61726b657420616e642074686520477265617420526563657373696f6e2ee2809d20495a41204a6f75726e616c0a2020202020202020202020206f66204c61626f7220506f6c69637920313a33202832303132293a20312d3231202877697468204b2e20462e205a696d6d65726d616e6e292e223b7d693a313b4f3a383a22737464436c617373223a313a7b733a31313a227075626c69636174696f6e223b733a3136353a22e2809c53686f72742d74696d6520776f726b3a20546865204765726d616e20616e7377657220746f2074686520477265617420526563657373696f6e2ee2809d20496e7465726e6174696f6e616c204c61626f7572205265766965770a2020202020202020202020203135323a32202832303133293a203238372d333035202877697468204b2e204272656e6b6520616e64204b2e20462e205a696d6d65726d616e6e292e223b7d7d),
({:id}, 37, 0, 0x4f3a383a22737464436c617373223a313a7b733a31313a22616666696c696174696f6e223b733a31323a22495a412c204765726d616e79223b7d),
({:id}, 38, 0, 0x4f3a383a22737464436c617373223a333a7b733a373a2263757272656e74223b733a37383a22446570757479204469726563746f72206f6620526573656172636820616e6420506572736f6e616c2041647669736f7220746f20746865204469726563746f722c20495a412c204765726d616e79223b733a343a2270617374223b733a303a22223b733a383a2261647669736f7279223b733a3235323a22496e7465726e6174696f6e616c204c61626f7572204f7267616e697a6174696f6e3b20496e7465726e6174696f6e616c204f7267616e697a6174696f6e20666f72204d6967726174696f6e3b204665646572616c0a202020202020202020202020202020416e74692d4469736372696d696e6174696f6e204167656e63793b204665646572616c204d696e6973747279206f662046696e616e63653b204665646572616c204d696e6973747279206f66204c61626f720a202020202020202020202020202020616e6420536f6369616c20416666616972732c20556e69746564204e6174696f6e7320446576656c6f706d656e742050726f6772616d223b7d),
({:id}, 39, 0, 0x4f3a383a22737464436c617373223a313a7b733a363a22646567726565223b733a34363a225068442045636f6e6f6d6963732c204672656520556e6976657273697479206f66204265726c696e2c2032303039223b7d),
({:id}, 40, 0, 0x4f3a383a22737464436c617373223a313a7b733a393a22696e74657265737473223b733a38323a224576616c756174696f6e206f66206c61626f72206d61726b657420706f6c69636965732c206d6967726174696f6e2c20656475636174696f6e2c0a2020202020202020206469736372696d696e6174696f6e223b7d);
    ";

}