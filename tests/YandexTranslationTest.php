<?php

namespace Bluora\Yandex\Tests;

use Bluora\Yandex\Translate;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

class YandexTranslationTest extends BaseTestCase
{
    /**
     * @var Translate
     */
    private $translate;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $app = new Container();
        $app->singleton('app', 'Illuminate\Config\Repository');
        $app->singleton('config', 'Illuminate\Config\Repository');
        Facade::setFacadeApplication($app);
        $app['app']->set('aliases.Config', Illuminate\Support\Facades\Config::class);
        $app['config']->set('services.yandex-translate.key', include_once('yandex.api.key.php'));
        $this->translate = new Translate();
    }

    /**
     * Test translation of a word with original and requested translation language provided.
     */
    public function testTranslate()
    {
        $from_word = 'Hello world!';
        $from_langauge = 'en';
        $to_langauge = 'fr';
        $to_word = 'Bonjour tout le monde!';
        $transation = $this->translate->translate($from_word, $from_langauge, $to_langauge);
        $this->assertEquals($to_word, (string) $transation);
        $this->assertEquals($from_word, $transation->getOriginal());
        $this->assertEquals('en', $transation->getOriginalLanguage());
        $this->assertEquals('fr', $transation->getTranslationLanguage());
    }

    /**
     * Test translation of a word with only the requested translation language provided.
     */
    public function testTranslateNoOriginalLanguage()
    {
        $from_word = 'Hello world!';
        $from_langauge = 'en';
        $to_langauge = 'fr';
        $to_word = 'Bonjour tout le monde!';
        $transation = $this->translate->translate($from_word, false, $to_langauge);

        $this->assertEquals($from_word, $transation->getOriginal());
        $this->assertEquals($to_word, $transation->getTranslation());
        $this->assertEquals($from_langauge, $transation->getOriginalLanguage());
        $this->assertEquals($to_langauge, $transation->getTranslationLanguage());
    }

    /**
     * Test translation of more than one word.
     */
    public function testTranslateMultipleWords()
    {
        $from_word = ['Hello world!', 'I love you'];
        $from_langauge = 'en';
        $to_langauge = 'fr';
        $to_word = ['Bonjour tout le monde!', 'Je vous aime'];
        $transation = $this->translate->translate($from_word, $from_langauge, $to_langauge);

        $this->assertEquals($from_word, $transation->getOriginal());
        $this->assertEquals($to_word, $transation->getTranslation());
    }

    /**
     * Test translation of array of words with specific (integer based) keys.
     */
    public function testTranslateMultipleWordsMatchingKeys()
    {
        $from_word = [22 => 'Hello world!', 30 => 'I love you'];
        $from_langauge = 'en';
        $to_langauge = 'fr';
        $to_word = [22 => 'Bonjour tout le monde!', 30 => 'Je vous aime'];
        $transation = $this->translate->translate($from_word, $from_langauge, $to_langauge);

        $this->assertEquals($from_word, $transation->getOriginal());
        $this->assertEquals($to_word, $transation->getTranslation());
    }

    /**
     * Test translation of array of words with specific (string based) keys.
     */
    public function testTranslateMultipleWordsMatchingKeysWithWords()
    {
        $from_word = ['first_word' => 'Hello world!', 'second_word' => 'I love you'];
        $from_langauge = 'en';
        $to_langauge = 'fr';
        $to_word = ['first_word' => 'Bonjour tout le monde!', 'second_word' => 'Je vous aime'];
        $transation = $this->translate->translate($from_word, $from_langauge, $to_langauge);

        $this->assertEquals($from_word, $transation->getOriginal());
        $this->assertEquals($to_word, $transation->getTranslation());
    }
}
