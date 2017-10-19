<?php

namespace backend\controllers;


use Yii;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\Sitemap;
use yii\web\UploadedFile;


class SitemapController extends Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                //'update' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        if (!($exists = Sitemap::exists())) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'File "sitemap.xml" is missed at frontend web root.'));
            $writable = false;
        } elseif (!($writable = Sitemap::writable())) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'File "sitemap.xml" is not writable. Please check file permissions.'));
        }

        $textModel = new SitemapTextForm();
        $fileModel = new SitemapUploadForm();
        $activeTab = null;

        if (Yii::$app->request->isPost) {
            if ($textModel->load(Yii::$app->request->post())) {
                $activeTab = 'text';
                if ($textModel->validate()) {
                    Sitemap::setContent($textModel->xml);
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'File "sitemap.xml" successfully updated.'));
                    return $this->redirect(['index']);
                }
            } else {
                $fileModel->xml = UploadedFile::getInstance($fileModel, 'xml');
                $activeTab = 'file';

                if ($fileModel->validate()) {
                    $fileModel->xml->saveAs(Sitemap::filePath());
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'File "sitemap.xml" successfully updated.'));
                    return $this->redirect(['index']);
                }
            }
        } else {
            $textModel->xml = Sitemap::getContent();
        }

        return $this->render('index', ['exists' => $exists, 'writable' => $writable, 'textModel' => $textModel, 'fileModel' => $fileModel, 'activeTab' => $activeTab]);
    }
}


class SitemapTextForm extends \yii\base\Model
{


    //use \common\components\ModelFirstErrorTrait;    

    public $xml;


    public function rules()
    {
        return [
            ['xml', 'filter', 'filter' => 'trim'],
            ['xml', 'required'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'xml' => Yii::t('app', 'XML Content'),
        ];
    }
}


class SitemapUploadForm extends \yii\base\Model
{


    //use \common\components\ModelFirstErrorTrait;    

    public $xml;


    public function rules()
    {
        return [
            ['xml', 'required'],
            ['xml', 'file', 'skipOnEmpty' => false, 'extensions' => 'xml'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'xml' => Yii::t('app', 'XML Content'),
        ];
    }
}
