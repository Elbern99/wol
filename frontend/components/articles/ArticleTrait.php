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
use yii\helpers\Url;
use frontend\components\articles\OrderBehavior;
use common\models\ArticleAuthor;


trait ArticleTrait
{


    protected function getMainArticleCategory()
    {

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


    protected function setArticleLang($code)
    {
        Yii::$app->language = $code;
    }


    private function addOrderQuery($query, $order)
    {

        switch ($order) {
            case OrderBehavior::DATE_DESC:
                $query->orderBy(['a.created_at' => SORT_DESC, 'a.id' => SORT_ASC]);
                break;
            case OrderBehavior::DATE_ASC:
                $query->orderBy(['a.created_at' => SORT_ASC, 'a.id' => SORT_ASC]);
                break;
            case OrderBehavior::AUTHOR_ASC:
                $query->leftJoin(ArticleAuthor::tableName() . ' as aa', 'aa.article_id = a.id')
                    ->leftJoin(Author::tableName() . ' as au', 'aa.author_id = au.id');

                $query->orderBy(['au.surname' => SORT_ASC, 'a.id' => SORT_ASC]);
                break;
            case OrderBehavior::AUTHOR_DESC:
                $query->leftJoin(ArticleAuthor::tableName() . ' as aa', 'aa.article_id = a.id')
                    ->leftJoin(Author::tableName() . ' as au', 'aa.author_id = au.id');

                $query->orderBy(['au.surname' => SORT_DESC, 'a.id' => SORT_ASC]);
                break;
            case OrderBehavior::TITLE_ASC:
                $query->orderBy(['a.title' => SORT_ASC, 'a.id' => SORT_ASC]);
                break;
            case OrderBehavior::TITLE_DESC:
                $query->orderBy(['a.title' => SORT_DESC, 'a.id' => SORT_ASC]);
                break;
            default:
                $query->orderBy(['a.created_at' => SORT_DESC, 'a.id' => SORT_ASC]);
                break;
        }
    }


    protected function getArticlesList($limit, $currentOnly = false)
    {
        $order = OrderBehavior::getArticleOrder();
        $query = Article::find()
            ->alias('a')
            ->select(['a.id', 'a.title', 'a.seo', 'a.availability', 'a.created_at'])
            ->where(['a.enabled' => 1])
            ->with(['articleCategories' => function($query) {
                    return $query->alias('ac')
                        ->select(['category_id', 'article_id'])
                        ->innerJoin(Category::tableName() . ' as c', 'ac.category_id = c.id AND c.lvl = 1');
                }])
            ->with(['articleAuthors.author' => function($query) {
                return $query->select(['id', 'url_key', 'name'])->asArray();
            }]);
            
        if ($currentOnly) {
            $query->andWhere(['a.is_current' => 1]);
        }

        $this->addOrderQuery($query, $order);
        return $query->limit($limit)->all();
    }


    protected function getLastArticlesList($limit)
    {

        return Article::find()
                ->alias('a')
                ->select(['a.id', 'a.title', 'a.seo', 'a.availability', 'a.created_at'])
                ->where(['a.enabled' => 1])
                ->with(['articleCategories' => function($query) {
                        return $query->alias('ac')
                            ->select(['category_id', 'article_id'])
                            ->innerJoin(Category::tableName() . ' as c', 'ac.category_id = c.id AND c.lvl = 1');
                    }])
                ->with(['articleAuthors.author' => function($query) {
                        return $query->select(['id', 'url_key', 'name'])->asArray();
                    }])
                ->orderBy(['a.created_at' => SORT_DESC])
                ->limit($limit)
                ->all();
    }


    protected function getArticleCount()
    {
        return Article::find()->where(['enabled' => 1])->count();
    }


    protected function getArticleCategories($slug)
    {

        return Article::find()
                ->with([
                    'articleCategories' => function($query) {
                        return $query->select(['category_id', 'article_id'])->asArray();
                    }
                ])
                ->where(['seo' => $slug, 'enabled' => 1])
                ->one();
    }


    protected function getFullCategoryArticlesArray($categoryIds)
    {

        return Category::find()
                ->alias('c1')
                ->where(['c1.id' => $categoryIds])
                ->select([
                    'c1.title', 'c1.url_key', 'c1.lvl'
                ])
                ->asArray()
                ->all();
    }


    protected function getArticleSlugModel($slug, $v = null)
    {

        $query = $model = Article::find()
            ->with([
                'articleAuthors.author' => function($query) {
                    return $query->select(['id', 'avatar', 'url_key', 'author_key'])->where(['enabled' => 1])->asArray();
                },
                'articleCategories' => function($query) {
                    return $query->select(['category_id', 'article_id'])->asArray();
                }
            ])
            ->where(['seo' => $slug, 'enabled' => 1]);

        if (null !== $v) {
            $query->andWhere(['version' => $v]);
        } else {
            $query->orderBy(['version' => SORT_DESC]);
        }

        return $query->one();
    }


    protected function renderArticlePage($template, $model, $multiLang = false, $langCode = null)
    {

        try {

            if (!is_object($model)) {
                throw new NotFoundHttpException('Page Not Found.');
            }

            $records = $model->getRelatedRecords();

            if ($model instanceof Article) {
                $articleCollection = $model->getCollection($multiLang);
            } else {
                $articleCollection = Yii::createObject(Collection::class);
                $articleCollection->initCollection($model::ENTITY_NAME, $model, $multiLang);
            }

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
                        'author_key' => $author->author['author_key'],
                        'name' => $name,
                        'affiliation' => $affiliation,
                        'avatar' => Author::getImageUrl($author->author['avatar']),
                        'profile' => Url::to([Author::AUTHOR_PREFIX . '/' . $author->author['url_key']], true)
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

            $model->setCurrentLang($currentLang);
            $model->renderTwitterTags();
        } catch (\Exception $e) {
            throw $e;
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
