<?php
namespace frontend\components\helpers;

use yii\helpers\Html;
use yii\helpers\Url;
use Yii;

class ShowMore {
    
    private $params = [];
    private $default = 10;
    
    public function addParam(array $data, string $key) {
        $this->params[$key] = $data;
    }
    
    public function getLimit($key) {
        
        if (!isset($this->params[$key])) {
            return $this->default;
        }
        
        $limit = $this->params[$key]['step'];
        
        if (Yii::$app->request->get($key)) {
            
            $step = (int)Yii::$app->request->get($key);
            
            if ($step) {
                $limit = $limit * ($step + 1);
            }
        }
        
        return $limit;
    }
    
    public function getLink($key) {
        
        if (!isset($this->params[$key])) {
            return '';
        }
        
        $step = Yii::$app->request->get($key) ?? 0;
        $current = $this->params[$key]['step'] * ($step + 1);
        
        if ($this->params[$key]['count'] > $current) {
            return Html::a("show more", Url::current([$key => $step + 1]), ['class' => 'btn-gray align-center', 'id' => $key.'_button']);
        } else {
            if ($step) {
                return Html::a("clear", Url::current([$key => 0]), ['class' => 'btn-gray align-center', 'id' => $key.'_button']);
            }
        }
        
        return '';
    }
}
