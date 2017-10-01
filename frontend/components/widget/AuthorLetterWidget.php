<?php

namespace frontend\components\widget;


use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\AuthorLetter;


class AuthorLetterWidget extends Widget
{


    public $filterLetter = null;

    private static $_letters = null;

    public $mode = 'basic';
    
    public $type = null;


    public function run()
    {
        $params = [
            'letters' => $this->getLetters(),
            'filterLetter' => $this->filterLetter,
            'type' => $this->type,
        ];

        return $this->render('author_letter_' . $this->mode, $params);
    }


    private function getLetters()
    {
        if (null === self::$_letters) {
            self::$_letters = AuthorLetter::find()->ordered()->joinWith(['stats'])->all();
        }

        return self::$_letters;
    }
}
