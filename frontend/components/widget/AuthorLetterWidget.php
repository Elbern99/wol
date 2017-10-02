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

    private static $_letters = [];

    public $mode = 'basic';

    public $type = null;


    public function run()
    {
        $params = [
            'letters' => $this->getLetters(),
            'filterLetter' => $this->filterLetter,
            'type' => $this->type,
            'relation' => $this->getRelationName($this->type),
        ];

        return $this->render('author_letter_' . $this->mode, $params);
    }


    private function getLetters()
    {
        $key = $this->type ? $this->type : 'null';

        if (!isset(self::$_letters[$key])) {
            $relation = $this->getRelationName($this->type);

            self::$_letters[$key] = AuthorLetter::find()->ordered()->joinWith([$relation])->all();
        }

        return self::$_letters[$key];
    }


    private function getRelationName($type)
    {
        switch ($this->type) {
            case 'expert':
                $relation = 'statsExpert';
                break;
            case 'editor':
                $relation = 'statsEditor';
                break;
            default:
                $relation = 'stats';
                break;
        }

        return $relation;
    }
}
