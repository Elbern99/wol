<?php
namespace frontend\controllers\traits;

use Yii;

use common\models\PressReleaseItem;
use common\models\Category;

trait PressReleasesTrait {
    
    protected function getMainCategory() 
    {
        return Category::find()->where([
            'url_key' => $this->key,
        ])
        ->one();
    }
    
    protected function getPressReleasesQuery($year = null, $month = null) {
        
        $newsQuery = PressReleaseItem::find()->orderBy(['created_at' => SORT_DESC]);
        
        if ($month && $year) {
           $newsQuery->andWhere([
                'MONTH(created_at)' => $month,
                'YEAR(created_at)' => $year,
            ]);
           
        } else if (!$month && $year) {
            $newsQuery->andWhere([
                'YEAR(created_at)' => $year,
            ]);
        }
        
        return $newsQuery->andWhere(['enabled' => 1]);
    }
    
    protected function getPressReleasesList($limit = 10, $year = null, $month = null) {
        
        return $this->getPressReleasesQuery($year, $month)->limit($limit)->all();
    }
    
    protected function getPressReleasesCount($year = null, $month = null) {
        
        return $this->getPressReleasesQuery($year, $month)->count();
    }
    
    protected function getPressReleasesArchive() {
        
        $data = PressReleaseItem::find()
                        ->select(['DISTINCT (EXTRACT(YEAR_MONTH FROM created_at)) as created_at'])
                        ->orderBy(['created_at' => SORT_DESC])
                        ->asArray()
                        ->andWhere(['enabled' => 1])
                        ->all();
        
        $dates = [];
        
        foreach ($data as $date) {
            $year = substr($date['created_at'], 0, 4);
            $dates[] = $year.'-'.substr($date['created_at'], 4);
        }

        return $dates;
    }
}