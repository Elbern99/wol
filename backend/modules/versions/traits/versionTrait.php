<?php
namespace backend\modules\versions\traits;

use common\models\Article;
use Yii;

trait versionTrait {
    
    protected $notices = null;
    
    
    protected function getArticleEntity() {
        
        $eav = Yii::$app->modules['eav_module']->components;
        $eavEntityClass = $eav['entity'];
        $eavTypeClass = $eav['type'];
        
        return $eavEntityClass::find()
                        ->alias('e')
                        ->innerJoin($eavTypeClass::tableName().' as t', 'e.type_id = t.id')
                        ->where(['t.name' => 'article', 'e.model_id' => $this->articleId])
                        ->one();
    }
    
    protected function removeOldVersion() {
        
        $entity = $this->getArticleEntity();
        $article = Article::findOne($this->articleId);
        
        if (is_null($article) || is_null($entity)) {
            throw new \Exception('Article '.$this->articleId.' Not Exists');
        }
        
        try {
            
            $this->notices = $article->notices;
            $article->delete();
            $entity->delete();
            
            return true;
        } catch (\yii\db\Exception $e) {
            throw new \Exception('Article was not delete '.$e->getMessage());
        }
        
        return false;
    }
    
    protected function updatedArticle() {
        
        $article = Article::findOne($this->articleId);
        $article->enabled = 1;
        $article->notices = $this->notices;
        $article->save();
    }
    
    protected function addArticleNotice() {
        
        if (isset($this->xml->teiHeader->revisionDesc)) {
            
            $stack = new \SplStack();
            
            if (!is_null($this->notices)) {
                $stack->unserialize($this->notices);
            }

            if (isset($this->xml->teiHeader->revisionDesc->change->p)) {
                
                foreach ($this->xml->teiHeader->revisionDesc->change->p as $notice) {
                    $stack->push((string)$notice); 
                }
            }

            if ($stack->count()) {
                $this->notices = $stack->serialize();
            }
        }
    }
}
