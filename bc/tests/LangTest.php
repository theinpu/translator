<?php
/**
 * User: anubis
 * Date: 06.06.14
 * Time: 17:11
 */

namespace bc\tests;

use bc\translator\Lang;

class LangTest extends \PHPUnit_Framework_TestCase {

    public function testGetLocales() {
        $locales = Lang::getLocales();
        $this->assertEquals(array('ru', 'en'), $locales);
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

}
 