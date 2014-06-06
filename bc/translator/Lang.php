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
    private static $localeFile;

    public static function getLocales() {
        self::checkConfig();
        $locales = self::$cfg->get('lang');
        return array_keys($locales);
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
        $locales = self::$cfg->get('lang');
        if(!isset($locales[$locale])) throw new \InvalidArgumentException('Locale not exists');
        $path = $locales[$locale];
        self::$localeFile = $path.'lang.php';
        if(!file_exists(self::$localeFile)) throw new \InvalidArgumentException('Lang file is missing. Check path in config');
        self::$locale = $locale;
        self::$lang = require(self::$localeFile);
    }

    public static function getLocale() {
        return self::$locale;
    }

    public static function get($key) {
        self::checkConfig();
        $generate = self::$cfg->get('generate');
        if(is_null(self::$lang) || is_null(self::$locale)) throw new \RuntimeException('Need to set locale first');
        if(!isset(self::$lang[$key])) {
            if($generate) {
                self::generateKey($key);
            }
            return $key;
        }
        if(empty(self::$lang[$key])) return $key;
        return self::$lang[$key];
    }

    private static function generateKey($key) {
        self::$lang[$key] = '';
        file_put_contents(self::$localeFile, "<?php \n\nreturn ".var_export(self::$lang, true).';');
    }

}