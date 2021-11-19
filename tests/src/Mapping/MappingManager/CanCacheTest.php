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
    public function test_init_flagsCache()
    {
        $manager = MappingManager::instance();
        $manager->needsCaching(false);
        $manager->repository()->clear();
        self::assertFalse($manager->needsCaching());

        $books = Books::instance();
        MappingManager::for($books);
        self::assertTrue($manager->needsCaching());
    }
}
