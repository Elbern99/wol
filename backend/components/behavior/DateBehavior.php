<?php
namespace backend\components\behavior;

use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class DateBehavior extends AttributeBehavior
{
 
    public $createdAtAttribute = 'created_at';
    public $updatedAtAttribute = 'updated_at';
    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_AFTER_FIND => [$this->createdAtAttribute, $this->updatedAtAttribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => [$this->createdAtAttribute, $this->updatedAtAttribute],
            ];
        }
    }
    
    public function evaluateAttributes($event)
    {
        if ($this->skipUpdateOnClean
            && $event->name == ActiveRecord::EVENT_BEFORE_UPDATE
            && empty($this->owner->dirtyAttributes)
        ) {
            return;
        }

        if (!empty($this->attributes[$event->name])) {
            $attributes = (array) $this->attributes[$event->name];
            $value = $this->getValue($event);

            foreach ($attributes as $attribute) {
                // ignore attribute names which are not string (e.g. when set by TimestampBehavior::updatedAtAttribute)
                if (is_string($attribute)) {
                    $this->owner->$attribute = $value[$attribute];
                }
            }
        }
    }

    /**
     * @inheritdoc
     *
     * In case, when the [[value]] is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
     * will be used as value.
     */
    protected function getValue($event)
    {
        
        if ($this->value === null) {
            
            if ($event->name == ActiveRecord::EVENT_BEFORE_UPDATE) {
                
                return [
                    'created_at' => strtotime($event->sender->created_at), 
                    'updated_at' =>strtotime($event->sender->updated_at)
                ];
            }
            
            return [
                'created_at' => date('d-M-Y', $event->sender->created_at), 
                'updated_at' => date('d-M-Y', $event->sender->updated_at)
            ];
        }
        return parent::getValue($event);
    }
    
}
