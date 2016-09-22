<?php
namespace frontend\components;

use yii\base\Widget;
use yii\helpers\Html;

/*
 * Widget init bottom menu
 */
class BottomMenuWidget extends Widget {
    
    public $query = null;
    private $items = [];
    public $options = [];
    
    public function init() {
        parent::init();
    }

    /*
     * Run widget
     * @return string
     */
    public function run() {
        
        $this->items = $this->query->all();
        echo $this->getContent();
    }
    
    /*
     * Create Html structure menu
     * @return string
     */
    protected function getContent() {
        $content = '';
        
        if (count($this->items)) {
            
            foreach ($this->items as $item) {
               
                if ($item->link) {
                    
                    if (preg_match("/^(http|https):\/\//", $item->link)) {
                        $options = ['target'=>'blank'];
                    } else {
                        $options = [];
                    }
                    
                    $text = Html::a($item->title, $item->link, $options);
                } else {
                    $text = $item->title;
                }
                    
                $content .= Html::tag('li', $text, ['class'=>$item->class]);
            }
            
            $content = Html::tag('ul', $content, $this->options);
        }
        
        return $content;
    }

}

