<?php

namespace Bluora\Yandex\Tests;

use \Mockery;
use \PHPUnit_Framework_TestCase;

/**
 * @package Bluora\Yandex\Tests
 */
abstract class BaseTestCase extends PHPUnit_Framework_TestCase
{

    /**
     * Tear down test.
     *
     * @return void
     */
    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }
}
