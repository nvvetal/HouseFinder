<?php

namespace HouseFinder\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BaseTest extends WebTestCase
{
    static $container;

    public static function setUpBeforeClass()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        self::$container = $kernel->getContainer();
    }

    public static function tearDownAfterClass()
    {

    }

}