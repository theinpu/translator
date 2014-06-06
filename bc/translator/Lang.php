<?php
/**
 * User: anubis
 * Date: 06.06.14
 * Time: 16:39
 */

namespace bc\translator;

use bc\config\Config;
use bc\config\ConfigManager;

class Lang {

    /**
     * @var Config
     */
    private static $cfg = null;

    private static $locale;
    private static $lang;

    public static function getLocales() {
        self::checkConfig();

        return array_keys(self::$cfg->getAll());
    }

    private static function checkConfig() {
        if(is_null(self::$cfg)) {
            self::$cfg = ConfigManager::get('config/lang');
        }
    }

    public static function setLocale($locale) {
        self::checkConfig();
        self::$locale = null;
        self::$lang = null;
        $path = self::$cfg->get($locale);
        $fileName = $path.'lang.php';
        if(!file_exists($fileName)) throw new \InvalidArgumentException('Lang file is missing. Check path in config');
        self::$locale = $locale;
        self::$lang = require($fileName);
    }

    public static function getLocale() {
        return self::$locale;
    }

    public static function get($key) {
        if(is_null(self::$lang) || is_null(self::$locale)) throw new \RuntimeException('Need to set locale first');
        if(!isset(self::$lang[$key])) return $key;
        if(empty(self::$lang[$key])) return $key;
        return self::$lang[$key];
    }

}