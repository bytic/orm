<?php

declare(strict_types=1);

namespace ByTIC\ORM\Tests\ORM;

use ByTIC\ORM\Managers\EntityManager;
use ByTIC\ORM\ORM;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Chapters\BooksChapters;

/**
 * Class HasManagersTest
 * @package ByTIC\ORM\Tests\ORM
 */
class HasManagersTest extends AbstractTest
{
    public function test_getManagerForClass_recordsManager()
    {
        $orm = ORM::instance();

        $manager = $orm->getManagerForClass(BooksChapters::class);
        self::assertInstanceOf(EntityManager::class, $manager);
    }
}