<?php
namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class NewsletterArchiveWidget extends Widget {
    
    public $data = [];
    protected $routePrefix = '/news/newsletters/';
    
    public function init()
    {
        parent::init();
    }
    
    private function getContent() {
        
        $html = '';
        
        if (count($this->data)) {
            
            $html .= '<ul class="articles-filter-list">';
            $years = [];
            
            foreach ($this->data as $data) {
                
                $year = date('Y',$data->getOldAttribute('date'));

                if (array_search($year, $years) === false) {
                    
                    $class = '';

                    if (count($years)) {
                        $html .= Html::endTag('ul');
                    }
                    
                    $years[] = $year;
                    
                    $html .= Html::beginTag('li', ['class' => 'item has-drop'.$class]);
                    $html .= '<div class="icon-arrow"></div>';
                    
                    $html .= Html::beginTag('strong');
                    $html .= Html::a($year, '#');
                    $html .= Html::endTag('strong');
                    $html .= Html::beginTag('ul',['class' => 'submenu'.$class]);
                }
                
                $html .= Html::beginTag('li', ['class' => 'item']);
//               $html .= Html::tag('div', date('M Y', $data->getOldAttribute('date')), ['class'=>'date']);
                $html .= Html::a($data['title'], $this->routePrefix.$data['url_key']);
                $html .= Html::endTag('li');
                
            }
            
            $html .= '</ul></ul>';
            unset($years);
        }
        
        return $html;
    }

    public function run()
    {
        return $this->getContent();
    }
    
}

