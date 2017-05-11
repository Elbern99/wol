<?php
namespace frontend\models;

use common\contracts\SearchXmlInterface;
use Yii;

/**
 * Signup form
 */
class SearchXmlData implements SearchXmlInterface
{
    private $type;
    
    public function __construct($type) {
        
        $this->type = $type;
    }
    
    public function getDataByIds($ids) {

        $basePath = Yii::$app->basePath.'/../';
        
        switch ($this->type) {
            case 'papers':
                $filename = $basePath.self::SPHINX_FOLDER.self::PAPERS_FILE_NAME;
                break;
            case 'policypapers':
                $filename = $basePath.self::SPHINX_FOLDER.self::POLICYPAPER_FILE_NAME;
                break;
            default:
                return false;
        }
        
        if (!file_exists($filename)) {
            return false;
        }
        
        $content = file_get_contents($filename);
        return $this->formatToArray(str_replace('sphinx:', '', $content), $ids);
    }
    
    protected function formatToArray($content, $ids) {
        
        $data = [];
        $items = new \SimpleXMLElement($content);
        $cnt = count($ids);
        
        foreach($items->document as $item) {
            
            $id = (string)$item->attributes();
            
            if (in_array($id, $ids)) {
                $param = (array)$item;
                $param['id'] = $id;
                $data[] = $param;
            }
            
            if ($cnt <= count($data)) {
                break;
            }
        }
        
        return $data;
    }
}
