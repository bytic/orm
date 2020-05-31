<?php

namespace Nip\Records\Tests\Mapping\MappingManager;

use Nip\Records\Mapping\MappingManager;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class CanCacheTest
 * @package Nip\Records\Tests\Mapping\MappingManager
 */
class CanCacheTest extends AbstractTest
{
    public function test_needsCaching()
    {
        $manager = new MappingManager();
        self::assertFalse($manager->needsCaching());

        $manager->needsCaching(false);
        self::assertFalse($manager->needsCaching());

        $manager->needsCaching(true);
        self::assertTrue($manager->needsCaching());

        $manager->needsCaching(0);
        self::assertTrue($manager->needsCaching());

        $manager->needsCaching(null);
        self::assertTrue($manager->needsCaching());

        $manager->needsCaching(false);
        self::assertFalse($manager->needsCaching());
    }

    public function test_init_flagsCache()
    {
        $manager = MappingManager::instance();
        self::assertFalse($manager->needsCaching());

        $books = Books::instance();
        MappingManager::for($books);
        self::assertTrue($manager->needsCaching());
    }
}