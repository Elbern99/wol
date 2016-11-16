<?php
namespace backend\modules\taxonomy;

use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use common\models\Category;
use Exception;
use Yii;
use common\models\Taxonomy;

class TaxonomyParser implements ParserInterface {
    
    private $xml;
    
    public function parse(ReaderInterface $reader) {
        
        $xml = $reader->getXml(); 
        $this->xml = new \SimpleXMLElement(file_get_contents($xml));
        $reader->removeTemporaryFolder();
        unset($reader);
        
        $this->categoryImport();
        $this->taxonomyDataImport();
        
        return true;
    }
    
    protected function getBehaviourSection($behaviour) {
        
        foreach ($this->xml->facet as $facet) {
            
            $attribute = (string)$facet->attributes();
            
            if ($attribute == $behaviour) {
                return $facet;
            }
        }
        
        throw new Exception('Behaviour '.$behaviour.' did not find');
    }
    
    protected function taxonomyDataImport() {
        
        $sections = [];
        $currentDate = time();
        
        try
        {
            $sections['collection'] = $this->getBehaviourSection('iwol-data-source-collection');
            $sections['dimension'] = $this->getBehaviourSection('iwol-data-source-dimension');
            $sections['perspective'] = $this->getBehaviourSection('iwol-method-perspective');
            $sections['technique'] = $this->getBehaviourSection('iwol-method-technique');
        
            foreach ($sections as $collection) {

                $bulkInsertArray = [];
                $facets = $this->setInvestedData($collection, []);
                $parent = current($facets);

                if (isset($parent['children'])) {

                    foreach ($parent['children'] as $facet) {

                        $key = key($facet);
                        $bulkInsertArray[] = [
                            'code' => $key,
                            'value' => $facet[$key],
                            'created_at' => $currentDate
                        ];
                    }

                    Yii::$app->db->createCommand()
                            ->batchInsert(
                                Taxonomy::tableName(),
                                ['code', 'value', 'created_at'],
                                $bulkInsertArray
                            )->execute();

                }

            }
        
        } catch (\yii\db\Exception $e) {
            throw new Exception('Categories cannot be saved error:'. $e->getMessage());
        } catch (\yii\db\Exception $e) {
            throw new Exception('Categories cannot be saved error:'. $e->getMessage());
        }
    }
    
    protected function categoryImport() {
        
        $taxonomy = $this->getBehaviourSection('subjects');
        $baseUrl = (string)$taxonomy->definition;
        $baseUrl = '/'.strtolower(str_replace(' ', '-', $baseUrl));
        
        $baseCategory = Category::find()->where(['url_key'=>'articles'])->one();
        
        try {
            
            foreach ($taxonomy->facet as $facet) {

                $categories = $this->setInvestedData($facet, []);

                foreach ($categories as $key=>$category) {
                            
                    if (is_array($category)) {
                        $baseTitle = $category[0];
                    } else {
                        $baseTitle = $category;
                    }
                    
                    $base = $this->importTaxonomy($baseCategory, $baseTitle, $baseUrl, $key);

                    if (isset($category['children'])) {

                        foreach ($category['children'] as $children) {

                            $k = key($children);
                            $this->importTaxonomy($base, $children[$k], $baseUrl, $k);
                        }
                    }


                }

            }
            
        } catch (\yii\db\Exception $e) {
            throw new Exception('Categories cannot be saved error:'. $e->getMessage());
        } catch (\yii\db\Exception $e) {
            throw new Exception('Categories cannot be saved error:'. $e->getMessage());
        }

    }
    
     protected function importTaxonomy($parent, $title, $base_url = '', $taxonomy_code = null) {

        $meta_title = $title;
        $url_key =  $base_url.'/'.preg_replace('/[^a-z]+/', '-', urlencode(strtolower($title)));

        if ($title && $meta_title && $url_key && is_object($parent)) {

            $category = new Category([
                'title' => $title,
                'meta_title' => $meta_title,
                'url_key' => $url_key,
                'system' => 0,
                'active' => 1,
                'visible_in_menu' => 1,
                'taxonomy_code' => $taxonomy_code
            ]);

            if ($category->appendTo($parent)) {
                return $category;
            }
        }

        return false;
    }

    protected function setInvestedData($taxonomy, $storage) {
        
        $p = xml_parser_create();
        xml_parse_into_struct($p, $taxonomy->asXML(), $vals);
        xml_parser_free($p);
        
        $id = $vals[0]['attributes']['XML:ID'];
        $current = [];
        
        if (count($taxonomy->facet)) {
            
            foreach ($taxonomy->facet as $facet) {
                
                $result = $this->setInvestedData($facet, []);
                $current[] = $result;
            }
        }
        
        if (count($current)) {
            $storage[$id] = array((string)$taxonomy->names->name, 'children' => $current);
        } else {
            $storage[$id] = (string)$taxonomy->names->name;
        }
        
        return $storage;
    }
    
    
}

