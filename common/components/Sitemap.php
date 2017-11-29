<?php

namespace common\components;


use Yii;
use yii\base\Object;


class Sitemap extends Object
{


    const FILE_PATH = '@frontend/web/sitemap.xml';


    /**
     * @return string
     */
    public static function filePath()
    {
        return Yii::getAlias(self::FILE_PATH);
    }


    /**
     * @return boolean
     */
    public static function exists()
    {
        return file_exists(self::filePath()) && is_file(self::filePath());
    }


    /**
     * @return boolean
     */
    public static function writable()
    {
        return is_writable(self::filePath());
    }


    /**
     * @return string
     */
    public static function getContent()
    {
        if (!self::exists()) {
            throw new \yii\base\Exception('File "sitemap.xml" does not exist.');
        }

        return file_get_contents(self::filePath());
    }


    public static function setContent($xml)
    {
        if (!self::exists()) {
            throw new \yii\base\Exception('File "sitemap.xml" does not exist.');
        }

        if (!self::writable()) {
            throw new \yii\base\Exception('File "sitemap.xml" is not writable.');
        }

        file_put_contents(self::filePath(), $xml);
    }
}
