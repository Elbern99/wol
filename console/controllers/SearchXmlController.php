<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use Yii;
use common\contracts\SearchXmlInterface;

class SearchXmlController extends Controller implements SearchXmlInterface {

    private $mode = 0775;
    
    /* php yii search-xml */
    public function actionIndex($index = null) {

        $basePath = Yii::$app->basePath.'/../';
        
        if (!isset(Yii::$app->params['iza-web-discussion']) || !isset(Yii::$app->params['iza-web-policy'])) {
            return 0;
        }
        
        if (!is_dir($basePath.self::WREB_FOLDER)) {
            
            if (!FileHelper::createDirectory($basePath.self::WREB_FOLDER, $this->mode, true)) {
                return 0;
            }
        }
        
        if (!is_dir(self::SPHINX_FOLDER)) {
            
            if (!FileHelper::createDirectory(self::SPHINX_FOLDER, $this->mode, true)) {
                return 0;
            }
        }

        //policypapers_dc.xml
        $izaWebPolicy = file_get_contents(Yii::$app->params['iza-web-policy']);
        //papers_dc.xml
        $izaWebDiscussion = file_get_contents(Yii::$app->params['iza-web-discussion']);
        
        if ($izaWebPolicy) {
            $handle = fopen($basePath.self::WREB_FOLDER.self::POLICYPAPER_FILE_NAME, "w");
            fwrite($handle, $izaWebPolicy);
            fclose($handle);

            $handle = fopen(self::SPHINX_FOLDER.self::POLICYPAPER_FILE_NAME, "w");
            fwrite($handle, $this->convertToShphinxXml($izaWebPolicy, 'policypapers'));
            fclose($handle);
            unset($izaWebPolicy);
        }
        
        if ($izaWebDiscussion) {
            $handle = fopen($basePath.self::WREB_FOLDER.self::PAPERS_FILE_NAME, "w");
            fwrite($handle, $izaWebDiscussion);
            fclose($handle);
            
            $handle = fopen(self::SPHINX_FOLDER.self::PAPERS_FILE_NAME, "w");
            fwrite($handle, $this->convertToShphinxXml($izaWebDiscussion, 'papers'));
            fclose($handle);
            unset($izaWebDiscussion);
        }

        return 1;
    }
    
    protected function convertToShphinxXml($xml, $type) {
        
        $sphinxXml = '<?xml version="1.0" encoding="utf-8"?>';
        $sphinxXml .= '<sphinx:docset>';
        
        $items = new \SimpleXMLElement(str_replace('dc:', '', $xml));
        $i = 1;
        
        foreach ($items->item as $item) {
            $sphinxXml .= '<sphinx:document id="'.$i.'">';
            $sphinxXml .= '<type>'.$type.'</type>';
            $sphinxXml .= preg_replace('/(<item>|<\/item>)/', '', $item->asXML());
            $sphinxXml .= '</sphinx:document>';
            $i++;
        }

        $sphinxXml .= '</sphinx:docset>';
        
        return $sphinxXml;
    }

}