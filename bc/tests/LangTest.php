<?php
/**
 * User: anubis
 * Date: 06.06.14
 * Time: 17:11
 */

namespace bc\tests;

use bc\config\ConfigManager;
use bc\translator\Lang;

class LangTest extends \PHPUnit_Framework_TestCase {

    public function testGetLocales() {
        $locales = Lang::getLocales();
        $this->assertEquals(array('ru' => 'русский', 'en' => 'English'), $locales);
    }

    public function testSetRULocale() {
        Lang::setLocale('ru');
        $this->assertEquals('ru', Lang::getLocale());
    }

    /**
     * @depends testSetRULocale
     */
    public function testRUMessage() {
        $this->assertEquals('тестовая строка', Lang::get('тестовая строка'));
    }

    public function testSetENLocale() {
        Lang::setLocale('en');
        $this->assertEquals('en', Lang::getLocale());
    }

    /**
     * @depends testSetENLocale
     */
    public function testENMessage() {
        $this->assertEquals('test string', Lang::get('тестовая строка'));
    }

    public function testMultiLang() {
        Lang::setLocale('ru');
        $this->assertEquals('тестовая строка', Lang::get('тестовая строка'));
        Lang::setLocale('en');
        $this->assertEquals('test string', Lang::get('тестовая строка'));
        Lang::setLocale('ru');
        $this->assertEquals('тестовая строка', Lang::get('тестовая строка'));
    }

    public function testGenerate() {
        Lang::get('новая строка');
        $langs = ConfigManager::get('config/lang')->get('lang');
        $test = require $langs['ru'].'lang.php';
        $this->assertEquals(array('тестовая строка' => '', 'новая строка' => ''), $test);
    }

    public static function setUpBeforeClass() {

        $ru = array(
            'тестовая строка' => '',
        );

        $en = array(
            'тестовая строка' => 'test string',
        );

        $langs = ConfigManager::get('config/lang')->get('lang');

        @mkdir($langs['ru'], 0777, true);
        file_put_contents($langs['ru'].'lang.php', "<?php \n\nreturn ".var_export($ru, true).';');

        @mkdir($langs['en'], 0777, true);
        file_put_contents($langs['en'].'lang.php', "<?php \n\nreturn ".var_export($en, true).';');
    }

    public static function tearDownAfterClass() {
        $langs = ConfigManager::get('config/lang')->get('lang');
        unlink($langs['ru'].'lang.php');
        unlink($langs['en'].'lang.php');
    }

}
 