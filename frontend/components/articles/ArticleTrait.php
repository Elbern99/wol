<?php
namespace frontend\components\articles;

use common\models\Category;
use common\models\Lang;
use common\models\Article;
use common\models\Author;
use common\modules\eav\Collection;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use Yii;
use common\modules\eav\helper\EavValueHelper;
use common\modules\eav\CategoryCollection;

trait ArticleTrait {
    
    protected function getMainArticleCategory() {
        
        return Category::find()
                            ->where(['url_key' => 'articles'])
                            ->select([
                                'root', 'lvl', 'lft', 'rgt',
                                'url_key', 'title', 
                                'description', 'meta_title',
                                'meta_keywords'
                            ])
                            ->one();
    }
    
    protected function getArticlesList($limit, $order) {
        
        return  Article::find()
                         ->select(['id', 'title', 'seo', 'availability', 'created_at'])
                         ->where(['enabled' => 1])
                         ->with(['articleCategories' => function($query) {
                             return $query->select(['category_id', 'article_id']);
                         }])
                         ->with(['articleAuthors.author' => function($query) {
                             return $query->select(['id','url_key', 'name'])->asArray();
                         }])
                         ->orderBy(['created_at' => $order])
                         ->limit($limit)
                         ->all();
    }
    
    protected function getArticleCount() {
        return Article::find()->where(['enabled' => 1])->count();
    }
    
    protected function getArticleCategories($slug) {
        
        return Article::find()
                    ->with([
                        'articleCategories' => function($query) {
                            return $query->select(['category_id', 'article_id'])->asArray();
                        }
                    ])
                    ->where(['seo' => $slug, 'enabled' => 1])
                    ->one();
    }
    
    protected function getFullCategoryArticlesArray($categoryIds) {
        
        return  Category::find()
                        ->alias('c1')
                        ->leftJoin([
                            'c2' => Category::tableName()], 'c2.lft < c1.lft and c2.rgt > c1.rgt'
                        )
                        ->where(['c1.id' => $categoryIds])
                        ->andWhere(['>=', 'c2.lvl', 1])
                        ->select([
                            'c2.id as p_id',
                            'c1.title', 'c1.url_key',
                            'c2.title as p_title', 'c2.url_key as p_url_key',
                        ])
                        ->asArray()
                        ->all();
    }
    
    protected function renderArticlePage($template, $slug, $multiLang = false, $langCode = null) {

        try {
            
            $model = Article::find()
                    ->with([
                        'articleAuthors.author' => function($query) {
                            return $query->select(['id', 'avatar', 'url_key'])->where(['enabled' => 1])->asArray();
                        }, 
                        'articleCategories' => function($query) {
                            return $query->select(['category_id', 'article_id'])->asArray();
                        }
                    ])
                    ->where(['seo' => $slug, 'enabled' => 1])
                    ->one();

            if (!is_object($model)) {
                throw new NotFoundHttpException('Page Not Found.');
            }
            
            $records = $model->getRelatedRecords();
            $articleCollection = Yii::createObject(Collection::class);
            $articleCollection->initCollection(Article::tableName(), $model, $multiLang);

            $values = $articleCollection->getEntity()->getValues();
            
            if (empty($values)) {
                throw new NotFoundHttpException('Page Not Found.');
            }
            
            unset($values);
            
            $authors = [];
            $categories = [];
            $langs = [];
            $currentLang = null;
            
            if (is_array($records['articleAuthors']) && count($records['articleAuthors'])) {
                
                $authorCollection = Yii::createObject(CategoryCollection::class);
                $authorCollection->setAttributeFilter(['affiliation', 'name']);
                $authorCollection->initCollection(Author::tableName(), ArrayHelper::getColumn($records['articleAuthors'], 'author_id'));
                $authorValues = $authorCollection->getValues();
                
                foreach ($records['articleAuthors'] as $author) {
                    
                    if (!isset($author->author['id'])) {
                        continue;
                    }

                    $name = EavValueHelper::getValue($authorValues[$author->author['id']], 'name', function($data) {
                        return $data;
                    });
                    
                    $affiliation = EavValueHelper::getValue($authorValues[$author->author['id']], 'affiliation', function($data) {
                        return $data->affiliation;
                    }, 'string');
                    
                    $authors[] = [
                        'name' => $name,
                        'affiliation' => $affiliation,
                        'avatar' => Author::getImageUrl($author->author['avatar']),
                        'profile' => Author::getAuthorUrl($author->author['url_key'])
                    ];
                }
            }

            if (count($records['articleCategories'])) {

                $categoryIds = ArrayHelper::getColumn($records['articleCategories'], 'category_id');
                $categories = $this->getFullCategoryArticlesArray($categoryIds);
                
            }
            
            if ($articleCollection->isMulti) {
                
                $currentLang = 0;
                
                if (is_null($langCode)) {
                    
                    $langIds = $articleCollection->getLanguages();
                    $langs = Lang::find()->where(['id' => $langIds])->asArray()->all();
                } else {
                    
                    $lang = Lang::find()->where(['code' => $langCode])->select(['id'])->one();

                    if (is_object($lang)) {
                        $currentLang = $lang->id;
                    }
                }
            }

        } catch (\Exception $e) {
            throw new NotFoundHttpException('Page Not Found.');
        } catch (\yii\db\Exception $e) {
            throw new NotFoundHttpException('Page Not Found.');
        }

        return $this->render($template, [
            'article' => $model,
            'collection' => $articleCollection,
            'authors' => $authors,
            'categories' => $categories,
            'currentLang' => $currentLang,
            'langs' => $langs
        ]);
    }
}

