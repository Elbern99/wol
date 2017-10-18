<?php
namespace common\components;

/*
 * Changed base request functionality
 */
class SimpleRequest extends \yii\web\Request {
    public $web;
    public $adminUrl;

}