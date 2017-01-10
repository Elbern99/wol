<?php
namespace common\components;

use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;

class DateBehavior extends AttributeBehavior
{
 
    public $dateAttribute = 'date';
    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_AFTER_FIND => [$this->dateAttribute]
            ];
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
            return date('d-M-Y', $event->sender->date);
        }
        return parent::getValue($event);
    }

}
