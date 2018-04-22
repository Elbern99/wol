<?php

namespace common\components;


use Yii;
use yii\base\Behavior;


class TwitterBehavior extends Behavior
{


    public $twitterEnabled = true;

    public $twitterCard = null;

    public $twitterSite = null;

    public $twitterTitle = null;

    public $twitterDescription = null;

    public $twitterImage = null;

    private $_tags = [
        'twitterCard',
        'twitterSite',
        'twitterTitle',
        'twitterDescription',
        'twitterImage',
    ];


    public function renderTwitterTags()
    {
        if (!$this->twitterEnabled) {
            return;
        }

        foreach ($this->_tags as $property) {
            $this->renderTwitterTag($property);
        }
    }


    private function renderTwitterTag($property)
    {
        $content = $this->fetchContent($property);

        if ($content) {
            $name = $this->getTagName($property);
            Yii::$app->view->registerMetaTag([
                'name' => $name,
                'content' => $content,
            ]);

            return true;
        } else {
            return false;
        }
    }


    private function fetchContent($property)
    {
        $content = $this->$property;

        if (!$content) {
            return null;
        }

        if (($content instanceof \Closure || is_array($content)) && is_callable($content)) {
            $result = (string) call_user_func($content, $this->owner);
        } elseif ($this->owner->hasAttribute($content) || $this->owner->hasProperty($content)) {
            $result = (string) $this->owner->$content;
        } else {
            $result = (string) $content;
        }
        
        if ($property == 'twitterDescription') {
            $result = $this->filterDescription($result);
        }
        
        return $result;
    }


    private function getTagName($property)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', ':$0', $property));
    }
    
    
    private function filterDescription($value)
    {
        $value = strip_tags(trim($value));
        $value = trim(\yii\helpers\StringHelper::truncate($value, 197));
        return $value;
    }
}

/*
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@flickr" />
<meta name="twitter:title" content="Small Island Developing States Photo Submission" />
<meta name="twitter:description" content="View the album on Flickr." />
<meta name="twitter:image" content="https://farm6.staticflickr.com/5510/14338202952_93595258ff_z.jpg" />
 * 
 */