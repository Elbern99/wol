<?php
namespace frontend\components\filters;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class NewsletterLatestArticlesWidget extends Widget {
    
    public $data = [];
    
    public function init()
    {
        parent::init();
    }
    
    private function getContent() {
        
        $html = '';
        
        if (count($this->data)) {
            
            $html .= '<ul class="sidebar-news-list">';
            
            foreach ($this->data as $data) {

                $html .= Html::beginTag('li', ['class' => 'item']);
                $html .= Html::beginTag('h3');
                $html .= Html::a($data['title'], '/articles/'.$data['seo']);
                $html .= Html::endTag('h3');
                if (count($data['articleAuthors'])) {
                    $html .= Html::tag('div', implode(', ', 
                        array_map(
                            function($item) {
                                return Html::a($item['author']['name'], '/authors/'.$item['author']['url_key']);
                            }, $data['articleAuthors']
                        )
                    ), ['class' => 'writers']);
                }
                $html .= Html::endTag('li');
            }
            
            $html .= '</ul>';
            
            if (count($this->data) > Yii::$app->params['latest_articles_sidebar_limit']) {
                $html .= '<a href="" class="more-link">
                                <span class="more">More</span>
                                <span class="less">Less</span>
                            </a>';
            }

        }
        
        return $html;
    }

    public function run()
    {
        return $this->getContent();
    }
    
}

