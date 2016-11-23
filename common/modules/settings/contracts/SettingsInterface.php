<?php
namespace common\modules\settings\contracts;

interface SettingsInterface {
    
    public static function initSettings();
    public static function get($key);
}