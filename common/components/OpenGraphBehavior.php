<?php

namespace common\components;

use Yii;
use yii\base\Behavior;


class OpenGraphBehavior extends Behavior
{
    public $enabled = true;

    public $title = null;
    public $image = null;
    public $type = null;

    private $_tags = [
        'title',
        'image',
        'type',
    ];


    public function renderOgTags()
    {
        if (!$this->enabled) {
            return;
        }

        foreach ($this->_tags as $property) {
            $this->renderTag($property);
        }
    }


    private function renderTag($property)
    {
        if ($content = $this->fetchContent($property)) {
            Yii::$app->view->registerMetaTag([
                'property' => 'og:' . $property,
                'content' => $content,
            ]);
        }
    }

    /**
     * @param $property
     * @return false|string
     */
    private function fetchContent($property)
    {
        if ($content = $this->$property) {
            if (($content instanceof \Closure || is_array($content)) && is_callable($content)) {
                return (string)$content($this->owner);
            } elseif ($this->owner->hasAttribute($content) || $this->owner->hasProperty($content)) {
                return (string)$this->owner->$content;
            } else {
                return (string)$content;
            }
        }

        return false;
    }
}