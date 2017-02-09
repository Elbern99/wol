<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\modules\author\Roles;
use yii\helpers\ArrayHelper;
use common\models\Author;
use common\modules\eav\CategoryCollection;
use common\modules\eav\helper\EavValueHelper;
use common\models\ExpertSearch;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;
use common\models\AuthorRoles;
use frontend\components\widget\SidebarWidget;
use frontend\models\AuthorSearchForm;
use yii\helpers\Url;

class AuthorsController extends Controller {

    use traits\ExpertTrait;
    use traits\ProfileTrait;

    public function actionIndex() {

        $filter = Yii::$app->request->get('filter');
        $searchModel = new AuthorSearchForm();
        $collection = [];
        $roles = new Roles();
        
        $query = Author::find()
                ->alias('a')
                ->select(['a.id', 'a.url_key', 'a.avatar'])
                ->innerJoin(AuthorRoles::tableName().' as ar', 'a.id = ar.author_id')
                ->orderBy('a.surname')
                ->with(['articleAuthors.article' => function($query) {
                    return $query->select(['id', 'seo', 'title']);
                }])
                ->where(['a.enabled' => 1, 'ar.role_id' => $roles->getTypeByLabel('author')]);
        
        if (Yii::$app->request->isPost && $searchModel->load(Yii::$app->request->post())) {
            if ($searchModel->validate()) {
                $query->andFilterWhere(['like', 'name', $searchModel->search]);
            }
        } else {
            if ($filter) {
                $query->andWhere('surname LIKE :filter', [':filter' => $filter.'%']);
            }
        }
        
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = Yii::$app->params['authors_limit'];
        $authors = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->setAttributeFilter(['name', 'affiliation']);
        $authorCollection->initCollection(Author::tableName(), ArrayHelper::getColumn($authors, 'id'));
        $authorValues = $authorCollection->getValues();

        foreach ($authors as $author) {

            $name = EavValueHelper::getValue($authorValues[$author['id']], 'name', function($data) {
                        return $data;
                    });
            $affiliation = EavValueHelper::getValue($authorValues[$author['id']], 'affiliation', function($data) {
                        return $data->affiliation;
                    }, 'string');
            $articles = [];

            if (!empty($author['articleAuthors'])) {
                $articles = ArrayHelper::getColumn($author['articleAuthors'], 'article');
            }

            $collection[$author['id']] = [
                'avatar' => Author::getImageUrl($author['avatar']),
                'url_key' => $author['url_key'],
                'name' => $name,
                'affiliation' => $affiliation,
                'articles' => $articles
            ];
        }

        return $this->render('authors_list', ['collection' => $collection, 'paginate' => $pages, 'searchModel' => $searchModel]);
    }

    public function actionProfile($url_key) {

        $author = Author::find()
                ->where(['url_key' => $url_key, 'enabled' => 1])
                ->one();

        if (!is_object($author)) {
            throw new NotFoundHttpException('Page Not Found.');
        }

        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->initCollection(Author::tableName(), $author->id);
        $authorValues = $authorCollection->getValues();

        $data = [
            'author' => $author,
            'country' => EavValueHelper::getValue($authorValues[$author->id], 'author_country', function($data){ return $data->code; }, 'array'),
            'testimonial' => EavValueHelper::getValue($authorValues[$author->id], 'testimonial', function($data) {
                        return $data->testimonial;
                    }, 'string'),
            'publications' => EavValueHelper::getValue($authorValues[$author->id], 'publications', function($data) {
                        return $data->publication;
                    }, 'array'),
            'affiliation' => EavValueHelper::getValue($authorValues[$author->id], 'affiliation', function($data) {
                        return $data->affiliation;
                    }, 'string'),
            'position' => EavValueHelper::getValue($authorValues[$author->id], 'position', function($data) {
                        return $data;
                    }),
            'degree' => EavValueHelper::getValue($authorValues[$author->id], 'degree', function($data) {
                        return $data->degree;
                    }, 'string'),
            'interests' => EavValueHelper::getValue($authorValues[$author->id], 'interests', function($data) {
                        return $data->interests;
                    }, 'string'),
            'expertise' => EavValueHelper::getValue($authorValues[$author->id], 'expertise', function($data) { return $data->expertise; }, 'array'),
            'experience_type' => EavValueHelper::getValue($authorValues[$author->id], 'experience_type', function($data) {
                        return ucfirst($data->expertise_type);
                    }, 'string'),
            'language' => EavValueHelper::getValue($authorValues[$author->id], 'language', function($data){ return $data; }, 'array'),
            //'experience_url' => EavValueHelper::getValue($authorValues[$author->id], 'experience_url', function($data) { return $data; }, 'array'),
            'roles' => $author->getAuthorRoles(true),
            'articles' => $this->getAuthorArticles($author->id)
        ];

        $widgets = new SidebarWidget('profile');

        return $this->render($this->getProfileTemplate(), ['author' => $data, 'subjectAreas' => $this->subjectAreas, 'widgets' => $widgets]);
    }

    public function actionExpert() {

        $limit = $this->getLimit();
        $finds = new ExpertSearch();
        $roles = new Roles();

        $expertCollection = [];
        $expertRoleId = $roles->getTypeByLabel('expert');

        $filter = $this->getFilterData(Author::tableName(), $expertRoleId);
        $finds->setFilter($filter);

        $loadSearch = false;

        if (Yii::$app->request->isPost && $finds->load(Yii::$app->request->post())) {

            if ($finds->validate()) {

                $results = $this->getSearchResult($finds);

                if (count($results)) {

                    $experstIds = ArrayHelper::getColumn($results, 'id');
                    $loadSearch = true;
                }
            }
        }

        if (!$loadSearch) {

            $experstIds = $this->getExpertIds($expertRoleId);
            $experstIds = ArrayHelper::getColumn($experstIds, 'author_id');
        }

        if (!count($experstIds)) {

            return $this->render('expert', [
                        'expertCollection' => $expertCollection,
                        'limit' => $limit,
                        'expertCount' => count($experstIds),
                        'search' => $finds,
                        'filter' => $filter
            ]);
        }


        $experts = Author::find()
                ->select(['id', 'avatar', 'author_key', 'url_key'])
                ->where(['enabled' => 1, 'id' => $experstIds])
                ->orderBy('surname');

        if (!$loadSearch) {
            $experts->limit($limit);
        }

        $experts = $experts->all();

        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->setAttributeFilter(['name', 'affiliation', 'experience_type', 'expertise', 'language', 'author_country']);
        $authorCollection->initCollection(Author::tableName(), ArrayHelper::getColumn($experts, function($model) {
                    return $model->id;
                }));
        $authorValues = $authorCollection->getValues();

        foreach ($experts as $expert) {

            if (!isset($authorValues[$expert->id])) {
                continue;
            }

            $name = EavValueHelper::getValue($authorValues[$expert->id], 'name', function($data) {
                        return $data;
                    });
            $experience_type = EavValueHelper::getValue($authorValues[$expert->id], 'experience_type', function($data) {
                        return $data->expertise_type;
                    }, 'array');
            $affiliation = EavValueHelper::getValue($authorValues[$expert->id], 'affiliation', function($data) {
                        return $data->affiliation;
                    }, 'string');
            $expertise = EavValueHelper::getValue($authorValues[$expert->id], 'expertise', function($data) {
                        return $data->expertise;
                    }, 'array');
            $language = EavValueHelper::getValue($authorValues[$expert->id], 'language', function($data) {
                        return $data->code;
                    }, 'array');
            $author_country = EavValueHelper::getValue($authorValues[$expert->id], 'author_country', function($data) {
                        return $data->code;
                    }, 'array');

            $data = [
                'affiliation' => $affiliation,
                'avatar' => ($expert->avatar) ? Author::getImageUrl($expert->avatar) : null,
                'name' => $name,
                'experience_type' => $experience_type,
                'expertise' => $expertise,
                'language' => $language,
                'author_country' => $author_country,
                'profile' => Url::to([$expert->getUrl(), 'type' => 'expert']),
            ];

            if (Yii::$app->request->isPost) {

                if ($finds->filtered($data)) {
                    continue;
                }
            }

            $expertCollection[$expert->id] = $data;
        }

        return $this->render('expert', [
            'expertCollection' => $expertCollection,
            'limit' => $limit,
            'expertCount' => count($experstIds),
            'search' => $finds,
            'filter' => $filter
        ]);
    }

    public function actionLetter() {

        $letter = Yii::$app->request->post('letter');

        if (!$letter) {
            throw new NotFoundHttpException();
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $authors = $this->findAuthorsByLetter($letter);
        $result = [];

        if (count($authors)) {

            foreach ($authors as $author) {
                $result[] = Html::a($author['name'], Author::getAuthorUrl($author['url_key']));
            }
        }

        return $result;
    }

    public function actionEditorial() {

        $collection = [];
        $roles = new Roles();
        $editorsRoleIds = $roles->getEditorGroup();

        $editorAuthor = AuthorRoles::find()->alias('a1')->select(['a1.role_id','a1.author_id', 'a.avatar', 'a.url_key'])
                                           ->innerJoin(Author::tableName().' as a', 'a.id = a1.author_id')
                                           ->andWhere(['a1.role_id' => $editorsRoleIds])
                                           ->andWhere(['a.enabled' => 1])
                                           ->orderBy('a.surname')
                                           ->asArray()
                                           ->all();

        $authorIds = ArrayHelper::getColumn($editorAuthor, 'author_id');
        
        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->setAttributeFilter(['name', 'affiliation', 'interests']);
        $authorCollection->initCollection(Author::tableName(), $authorIds);
        $authorValues = $authorCollection->getValues();
        
        foreach ($editorAuthor as $data) {
            
            $name = EavValueHelper::getValue($authorValues[$data['author_id']], 'name', function($data) {
                        return $data;
                    });

            $affiliation = EavValueHelper::getValue($authorValues[$data['author_id']], 'affiliation', function($data) {
                        return $data->affiliation;
                    }, 'string');
                    
            $interest = EavValueHelper::getValue($authorValues[$data['author_id']], 'interests', function($data) {
                        return $data->interests;
                    }, 'string');
            
            $userData = [
                'name' => $name,
                'affiliation' => $affiliation,
                'avatar' => Author::getImageUrl($data['avatar']),
                'profile' => Url::to([Author::getAuthorUrl($data['url_key']), 'type' => 'editor']),
                'interest' => $interest
            ];

            $collection[$roles->getTypeByKey($data['role_id'])][] = $userData;
        }
        
        $widgets = new SidebarWidget('editorial_board');
        $top = [];
        
        if (isset($collection['chiefEditor'])) {
            $top = array_merge($top, $collection['chiefEditor']);
            unset($collection['chiefEditor']);
        }
        
        if (isset($collection['managingEditor'])) {
            $top = array_merge($top, $collection['managingEditor']);
            unset($collection['chiefEditor']);
        }
        
        return $this->render('editorial-board', [
            'top' => $top,
            'collection' => $collection, 
            'roles' => $roles,
            'widgets' => $widgets
        ]);      
    }

}
