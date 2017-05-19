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
            
            $filesPath = $article->getSavePath();
            $article->delete();
            $entity->delete();
            $this->removeOldFolders($filesPath);
            
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
    
    protected function removeOldFolders($path) {

        $folders = [];
        $folders['frontend'] = Yii::getAlias('@frontend') . '/web' . $path;
        $folders['backend'] = Yii::getAlias('@backend') . '/web' . $path;

        foreach ($folders as $dir) {

            if (is_dir($dir)) {
                $it = new \RecursiveDirectoryIterator($dir);
                $it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

                foreach ($it as $file) {

                    if ('.' === $file->getBasename() || '..' === $file->getBasename())
                        continue;

                    if ($file->isDir())
                        rmdir($file->getPathname());

                    @unlink($file->getPathname());
                }

                @rmdir($dir);
            }
        }
    }

}
