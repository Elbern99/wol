<?php

namespace backend\controllers\traits;

use Yii;

/*
 * Extension for cms widget on page
 */
trait HelpTrait {
  
    protected function getDiff($selected, $current) {

        if (empty($selected) && !empty($current)) {     
            
            $diff['delete'] = $current;
            $diff['add'] = [];
        } elseif (empty($current) && !empty($selected)) {   
            
            $diff['delete'] = [];
            $diff['add'] = $selected;
        } elseif (!empty($selected) && !empty($current)) {
            
            $diff['delete'] = [];
            
            foreach ($current as $id) {
                if (array_search($id, $selected) === false) {
                    $diff['delete'][] = $id;
                }
            }

            $diff['add'] = array_diff(array_merge($diff['delete'], $selected), $current);
        } else {
            
            $diff['delete'] = [];
            $diff['add'] = [];
        }

        return $diff;
    }
}
    
